<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Progress;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function topics()
    {
        $user = Auth::user();
        $topics = Topic::where('kelas_level', $user->kelas)
            ->where('is_active', true)
            ->withCount('lessons')
            ->orderBy('order')
            ->get()
            ->map(function ($topic) use ($user) {
                $completedCount = Progress::where('user_id', $user->id)
                    ->where('topic_id', $topic->id)
                    ->where('status', 'selesai')
                    ->count();
                $topic->progress_percent = $topic->lessons_count > 0
                    ? round(($completedCount / $topic->lessons_count) * 100)
                    : 0;
                return $topic;
            });

        return view('student.topics.index', compact('topics'));
    }

    public function showTopic(Topic $topic)
    {
        $user = Auth::user();
        $lessons = $topic->lessons()->where('is_active', true)->orderBy('order')->get();

        $lessons->each(function ($lesson) use ($user) {
            $progress = Progress::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->first();
            $lesson->user_status = $progress ? $progress->status : 'belum';
        });

        return view('student.topics.show', compact('topic', 'lessons'));
    }

    public function showLesson(Lesson $lesson)
    {
        $user = Auth::user();
        $lesson->load('topic');

        // Mark as 'sedang' if not yet started
        Progress::firstOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['topic_id' => $lesson->topic_id, 'status' => 'sedang']
        );

        $nextLesson = Lesson::where('topic_id', $lesson->topic_id)
            ->where('order', '>', $lesson->order)
            ->where('is_active', true)
            ->orderBy('order')
            ->first();

        $prevLesson = Lesson::where('topic_id', $lesson->topic_id)
            ->where('order', '<', $lesson->order)
            ->where('is_active', true)
            ->orderByDesc('order')
            ->first();

        return view('student.lessons.show', compact('lesson', 'nextLesson', 'prevLesson'));
    }

    public function completeLesson(Lesson $lesson)
    {
        $user = Auth::user();

        $progress = Progress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            [
                'topic_id' => $lesson->topic_id,
                'status' => 'selesai',
                'completed_at' => now(),
            ]
        );

        // Award points for completing lesson
        $user->addScore(20);

        // Check for badges
        app(\App\Services\GamificationService::class)->checkBadges($user);

        return redirect()->route('siswa.topics.show', $lesson->topic)
            ->with('success', 'Selamat! Kamu telah menyelesaikan materi "' . $lesson->title . '"! 🎉');
    }
}
