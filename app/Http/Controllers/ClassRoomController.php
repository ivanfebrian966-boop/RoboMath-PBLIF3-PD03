<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\QuizAttempt;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
        $topics = Topic::where('is_active', true)
            ->where('review_status', 'approved')
            ->orderBy('kelas_level')
            ->orderBy('order')
            ->get();

        return view('teacher.classes.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'kelas_level' => ['required', 'integer', 'min:1', 'max:6'],
            'topic_ids' => ['required', 'array', 'min:1'],
            'topic_ids.*' => ['integer', 'exists:topik,id'],
        ]);

        $topicIds = array_unique($validated['topic_ids']);
        $validTopicCount = Topic::whereIn('id', $topicIds)
            ->where('kelas_level', $validated['kelas_level'])
            ->where('is_active', true)
            ->where('review_status', 'approved')
            ->count();

        if ($validTopicCount !== count($topicIds)) {
            throw ValidationException::withMessages([
                'topic_ids' => 'Pilih topik yang sudah disetujui admin dan sesuai dengan tingkat kelas.',
            ]);
        }

        $code = strtoupper(Str::random(6));

        // Ensure unique code
        while (ClassRoom::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(6));
        }

        $classRoom = DB::transaction(function () use ($validated, $topicIds, $code): ClassRoom {
            $classRoom = ClassRoom::create([
                'teacher_id' => Auth::id(),
                'name' => $validated['name'],
                'code' => $code,
                'kelas_level' => $validated['kelas_level'],
            ]);

            $classRoom->topics()->sync($topicIds);

            return $classRoom;
        });

        return redirect()->route('guru.classes.index')
            ->with('class_created', [
                'name' => $classRoom->name,
                'code' => $classRoom->code,
            ]);
    }

    public function show(ClassRoom $classRoom)
    {
        if ($classRoom->teacher_id !== Auth::id()) {
            abort(403);
        }
        $classRoom->load(['students' => fn ($q) => $q->orderBy('name'), 'teacher', 'topics']);
        $topics = $classRoom->topics;

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
     * Siswa: Form masukan kode kelas & daftar topik soal AI yang terbuka
     */
    public function joinForm()
    {
        $user = Auth::user();
        $myClasses = $user->classRooms()
            ->with([
                'teacher',
                'topics' => function ($query) {
                    $query->where('topik.is_active', true)
                        ->where('topik.review_status', 'approved')
                        ->withCount(['quizzes' => function ($q) {
                            $q->where('is_active', true)
                                ->where('review_status', 'approved');
                        }]);
                },
                'topics.documentSummary',
            ])
            ->latest('anggota_kelas.created_at')
            ->get();

        // Hitung kuis yang sudah diselesaikan siswa per topik
        $allTopicIds = $myClasses->flatMap->topics->pluck('id')->unique();
        $completedQuizCountByTopic = [];

        if ($allTopicIds->isNotEmpty()) {
            $userAttempts = QuizAttempt::where('user_id', $user->id)
                ->where('is_correct', true)
                ->whereHas('quiz', fn ($q) => $q->whereIn('topic_id', $allTopicIds))
                ->with('quiz:id,topic_id')
                ->get()
                ->groupBy('quiz.topic_id');

            foreach ($allTopicIds as $tId) {
                $completedQuizCountByTopic[$tId] = $userAttempts->get($tId, collect())->pluck('quiz_id')->unique()->count();
            }
        }

        return view('student.classes.join', compact('myClasses', 'completedQuizCountByTopic'));
    }

    /**
     * Siswa: Proses masuk kelas dengan kode unik dari guru
     */
    public function join(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => 'Silakan masukkan 6 digit kode unik kelas dari gurumu.',
            'code.size' => 'Kode unik kelas harus terdiri dari tepat 6 karakter.',
        ]);

        $code = strtoupper(trim($request->code));

        $classRoom = ClassRoom::where('code', $code)
            ->where('is_active', true)
            ->with(['teacher', 'topics' => fn ($q) => $q->where('is_active', true)->where('review_status', 'approved')])
            ->first();

        if (! $classRoom) {
            return back()->withErrors([
                'code' => "Kode kelas \"{$code}\" tidak ditemukan atau kelas sedang dinonaktifkan. Mohon periksa kembali kode unik dari gurumu.",
            ])->withInput();
        }

        $user = Auth::user();

        if ($classRoom->students()->where('student_id', $user->id)->exists()) {
            return back()->with('info', "Kamu sudah bergabung di kelas \"{$classRoom->name}\". Kamu bisa langsung mengerjakan paket soal topik AI di bawah!")->withInput();
        }

        // Jika tingkat kelas siswa belum diatur, otomatis sesuaikan dengan kelas yang dimasuki
        if (! $user->kelas) {
            $user->update(['kelas' => $classRoom->kelas_level]);
        } elseif ((int) $user->kelas !== (int) $classRoom->kelas_level) {
            return back()->withErrors([
                'code' => "Tingkat kelas kamu (Kelas {$user->kelas} SD) tidak sesuai dengan kelas ini (Kelas {$classRoom->kelas_level} SD). Minta kode kelas yang sesuai tingkat kelasmu kepada gurumu.",
            ])->withInput();
        }

        $classRoom->students()->attach($user->id, ['joined_at' => now()]);

        $teacherName = $classRoom->teacher?->name ?? 'Guru';
        $topicCount = $classRoom->topics->count();
        $message = "Hore! Berhasil bergabung ke kelas \"{$classRoom->name}\" ({$teacherName}). ";
        if ($topicCount > 0) {
            $message .= "{$topicCount} topik latihan soal AI sekarang sudah terbuka dan siap kamu kerjakan! 🚀";
        } else {
            $message .= 'Topik soal AI dari gurumu akan segera tersedia di kelas ini! ✨';
        }

        return redirect()->route('siswa.kelas.join')->with('success', $message);
    }
}
