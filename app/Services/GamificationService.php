<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Progress;
use App\Models\QuizAttempt;
use App\Models\Topic;
use App\Models\User;

class GamificationService
{
    /**
     * Check and award badges based on user's achievements.
     */
    public function checkBadges(User $user): array
    {
        $awardedBadges = [];
        $badges = Badge::all();

        foreach ($badges as $badge) {
            // Skip already earned badges
            if ($user->badges()->where('badge_id', $badge->id)->exists()) {
                continue;
            }

            $earned = match ($badge->requirement_type) {
                'score' => $user->total_score >= $badge->requirement_value,
                'lessons_completed' => $this->getCompletedLessonsCount($user) >= $badge->requirement_value,
                'quizzes_correct' => $this->getCorrectQuizzesCount($user) >= $badge->requirement_value,
                'streak' => false, // Future: streak tracking
                'topic_mastered' => $this->getMasteredTopicsCount($user) >= $badge->requirement_value,
                default => false,
            };

            if ($earned) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
                $awardedBadges[] = $badge;
            }
        }

        // Update user level
        $user->updateLevel();

        return $awardedBadges;
    }

    private function getCompletedLessonsCount(User $user): int
    {
        return Progress::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->count();
    }

    private function getCorrectQuizzesCount(User $user): int
    {
        return QuizAttempt::where('user_id', $user->id)
            ->where('is_correct', true)
            ->count();
    }

    private function getMasteredTopicsCount(User $user): int
    {
        // A topic is "mastered" if accuracy >= 80%
        $topics = Topic::where('kelas_level', $user->kelas)->get();
        $mastered = 0;

        foreach ($topics as $topic) {
            $attempts = QuizAttempt::where('user_id', $user->id)
                ->whereHas('quiz', fn ($q) => $q->where('topic_id', $topic->id))
                ->get();

            if ($attempts->count() >= 5) {
                $accuracy = $attempts->where('is_correct', true)->count() / $attempts->count();
                if ($accuracy >= 0.8) {
                    $mastered++;
                }
            }
        }

        return $mastered;
    }
}
