<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClassRoomController extends Controller
{
    /**
     * Daftar kelas yang diajar guru
     */
    public function index()
    {
        $user = Auth::user();
        $classRooms = ClassRoom::where('teacher_id', $user->id)
            ->withCount('students')
            ->latest()
            ->get();

        return view('teacher.classes.index', compact('classRooms'));
    }

    public function create()
    {
        $topics = Topic::where('is_active', true)->orderBy('kelas_level')->orderBy('order')->get();

        return view('teacher.classes.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'kelas_level' => ['required', 'integer', 'min:1', 'max:6'],
            'topic_ids' => ['nullable', 'array'],
            'topic_ids.*' => ['integer', 'exists:topics,id'],
        ]);

        $code = strtoupper(Str::random(6));

        // Ensure unique code
        while (ClassRoom::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(6));
        }

        ClassRoom::create([
            'teacher_id' => Auth::id(),
            'name' => $request->name,
            'code' => $code,
            'kelas_level' => $request->kelas_level,
            'topic_ids' => $request->topic_ids ?? [],
        ]);

        return redirect()->route('guru.classes.index')
            ->with('success', "Kelas \"{$request->name}\" berhasil dibuat! Kode kelas: {$code} 🎉");
    }

    public function show(ClassRoom $classRoom)
    {
        if ($classRoom->teacher_id !== Auth::id()) {
            abort(403);
        }
        $classRoom->load(['students' => fn ($q) => $q->orderBy('name'), 'teacher']);
        $topics = Topic::whereIn('id', $classRoom->topic_ids ?? [])->get();

        return view('teacher.classes.show', compact('classRoom', 'topics'));
    }

    public function destroy(ClassRoom $classRoom)
    {
        if ($classRoom->teacher_id !== Auth::id()) {
            abort(403);
        }
        $classRoom->delete();

        return redirect()->route('guru.classes.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    /**
     * Siswa: Form join kelas
     */
    public function joinForm()
    {
        $user = Auth::user();
        $myClasses = $user->classRooms()->with('teacher')->get();

        return view('student.classes.join', compact('myClasses'));
    }

    /**
     * Siswa: Proses join kelas dengan kode
     */
    public function join(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $classRoom = ClassRoom::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();

        if (! $classRoom) {
            return back()->withErrors(['code' => 'Kode kelas tidak ditemukan atau tidak aktif.']);
        }

        $user = Auth::user();

        if ($classRoom->students()->where('student_id', $user->id)->exists()) {
            return back()->withErrors(['code' => 'Kamu sudah bergabung di kelas ini.']);
        }

        $classRoom->students()->attach($user->id, ['joined_at' => now()]);

        return back()->with('success', "Berhasil bergabung ke kelas \"{$classRoom->name}\"! 🎉");
    }
}
