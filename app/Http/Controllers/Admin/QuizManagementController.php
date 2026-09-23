<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Topic;
use Illuminate\Http\Request;

class QuizManagementController extends Controller
{
    public function index(Request $request)
    {
        $topicFilter = $request->query('topic_id');
        $typeFilter = $request->query('type');

        $query = Quiz::with('topic')->latest();

        if ($topicFilter) {
            $query->where('topic_id', $topicFilter);
        }
        if ($typeFilter) {
            $query->where('type', $typeFilter);
        }

        $quizzes = $query->paginate(20);
        $topics = Topic::where('is_active', true)->orderBy('kelas_level')->orderBy('order')->get();

        return view('admin.quizzes.index', compact('quizzes', 'topics', 'topicFilter', 'typeFilter'));
    }

    public function create()
    {
        $topics = Topic::where('is_active', true)->orderBy('kelas_level')->orderBy('order')->get();
        $lessons = Lesson::with('topic')->orderBy('topic_id')->orderBy('order')->get();

        return view('admin.quizzes.create', compact('topics', 'lessons'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
            'type' => ['required', 'in:pilihan_ganda,drag_drop,cerita_logika'],
            'question' => ['required', 'string'],
            'options' => ['required', 'array', 'min:2'],
            'options.*' => ['required', 'string'],
            'correct_answer' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'difficulty' => ['required', 'in:mudah,sedang,sulit'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        Quiz::create($data);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Soal berhasil ditambahkan! ✅');
    }

    public function edit(Quiz $quiz)
    {
        $topics = Topic::where('is_active', true)->orderBy('kelas_level')->orderBy('order')->get();
        $lessons = Lesson::with('topic')->orderBy('topic_id')->orderBy('order')->get();

        return view('admin.quizzes.edit', compact('quiz', 'topics', 'lessons'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
            'type' => ['required', 'in:pilihan_ganda,drag_drop,cerita_logika'],
            'question' => ['required', 'string'],
            'options' => ['required', 'array', 'min:2'],
            'options.*' => ['required', 'string'],
            'correct_answer' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'difficulty' => ['required', 'in:mudah,sedang,sulit'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $quiz->update($data);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Soal berhasil diperbarui! ✅');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Soal berhasil dihapus.');
    }
}
