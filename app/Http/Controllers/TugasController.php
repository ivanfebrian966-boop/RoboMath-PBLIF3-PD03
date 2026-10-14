<?php

namespace App\Http\Controllers;

use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TugasController extends Controller
{
    /**
     * Daftar tugas untuk guru — berdasarkan kelas yang diajarkan.
     */
    public function index(): View
    {
        $user = Auth::user();

        $tugasList = Tugas::whereHas('classRoom', function ($q) use ($user) {
            $q->where('teacher_id', $user->id);
        })->with(['classRoom', 'topic'])->latest()->get();

        return view('teacher.tugas.index', compact('user', 'tugasList'));
    }

    public function create(): View
    {
        $user = Auth::user();
        $classes = $user->teachingClassRooms()->where('is_active', true)->get();

        return view('teacher.tugas.create', compact('user', 'classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_room_id' => ['required', 'integer', 'exists:class_rooms,id'],
            'judul' => ['required', 'string', 'max:255'],
            'tenggat_waktu' => ['nullable', 'date'],
            'nilai_lulus' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        Tugas::create($validated);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil dibuat!');
    }

    public function show(Tugas $tugas): View
    {
        $this->authorizeOwner($tugas);

        $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->with('student')
            ->get();

        return view('teacher.tugas.show', compact('tugas', 'pengumpulan'));
    }

    public function destroy(Tugas $tugas): RedirectResponse
    {
        $this->authorizeOwner($tugas);
        $tugas->delete();

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas dihapus.');
    }

    // ─────────────────────────────────────────────────────────
    // Siswa: kumpulkan tugas
    // ─────────────────────────────────────────────────────────

    public function tugasSiswa(): View
    {
        $user = Auth::user();

        $tugasList = Tugas::whereHas('classRoom.students', fn ($q) => $q->where('users.id', $user->id))
            ->with(['classRoom', 'topic'])
            ->latest()
            ->get()
            ->map(function ($tugas) use ($user) {
                $tugas->pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
                    ->where('student_id', $user->id)
                    ->first();

                return $tugas;
            });

        return view('student.tugas.index', compact('user', 'tugasList'));
    }

    public function kumpulkanTugas(Request $request, Tugas $tugas): RedirectResponse
    {
        $user = Auth::user();

        PengumpulanTugas::updateOrCreate(
            ['tugas_id' => $tugas->id, 'student_id' => $user->id],
            ['status' => 'dikirim']
        );

        return redirect()->route('siswa.tugas.index')->with('success', 'Tugas berhasil dikumpulkan!');
    }

    // ─────────────────────────────────────────────────────────

    private function authorizeOwner(Tugas $tugas): void
    {
        $user = Auth::user();
        abort_unless($tugas->classRoom->teacher_id === $user->id || $user->isAdmin(), 403);
    }
}
