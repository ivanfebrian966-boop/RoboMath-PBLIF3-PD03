<?php

use App\Http\Controllers\Admin\AiRecommendationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LessonManagementController;
use App\Http\Controllers\Admin\QuizManagementController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ParentDashboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RoboMath Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================================
// SISWA Routes
// ============================================================
Route::middleware(['auth', RoleMiddleware::class.':siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/achievements', [StudentDashboardController::class, 'achievements'])->name('achievements');

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    // Topics & Lessons
    Route::get('/topics', [LessonController::class, 'topics'])->name('topics');
    Route::get('/topics/{topic}', [LessonController::class, 'showTopic'])->name('topics.show');
    Route::get('/lessons/{lesson}', [LessonController::class, 'showLesson'])->name('lessons.show');
    Route::post('/lessons/{lesson}/complete', [LessonController::class, 'completeLesson'])->name('lessons.complete');

    // Quizzes
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz');
    Route::get('/quiz/{topic}/play', [QuizController::class, 'play'])->name('quiz.play');
    Route::post('/quiz/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/{topic}/result', [QuizController::class, 'result'])->name('quiz.result');

    // Kelas
    Route::get('/kelas/join', [ClassRoomController::class, 'joinForm'])->name('kelas.join');
    Route::post('/kelas/join', [ClassRoomController::class, 'join'])->name('kelas.join.store');

    // Chatbot
    Route::post('/chat/send', [ChatbotController::class, 'send'])->name('chat.send');
    Route::get('/chat/history', [ChatbotController::class, 'history'])->name('chat.history');
});

// ============================================================
// GURU Routes
// ============================================================
Route::middleware(['auth', RoleMiddleware::class.':guru,admin'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/students/{student}', [TeacherDashboardController::class, 'studentDetail'])->name('students.show');
    Route::get('/laporan/download', [TeacherDashboardController::class, 'downloadReport'])->name('laporan.download');

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'teacherLeaderboard'])->name('leaderboard');

    // Manajemen Kelas
    Route::get('/kelas', [ClassRoomController::class, 'index'])->name('classes.index');
    Route::get('/kelas/buat', [ClassRoomController::class, 'create'])->name('classes.create');
    Route::post('/kelas', [ClassRoomController::class, 'store'])->name('classes.store');
    Route::get('/kelas/{classRoom}', [ClassRoomController::class, 'show'])->name('classes.show');
    Route::delete('/kelas/{classRoom}', [ClassRoomController::class, 'destroy'])->name('classes.destroy');
});

// ============================================================
// ORANGTUA Routes
// ============================================================
Route::middleware(['auth', RoleMiddleware::class.':orangtua'])->prefix('orangtua')->name('orangtua.')->group(function () {
    Route::get('/dashboard', [ParentDashboardController::class, 'dashboard'])->name('dashboard');
});

// ============================================================
// ADMIN Routes
// ============================================================
Route::middleware(['auth', RoleMiddleware::class.':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Soal
    Route::get('/quizzes', [QuizManagementController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/create', [QuizManagementController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes', [QuizManagementController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}/edit', [QuizManagementController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [QuizManagementController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [QuizManagementController::class, 'destroy'])->name('quizzes.destroy');

    // Manajemen Materi
    Route::get('/lessons', [LessonManagementController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/create', [LessonManagementController::class, 'create'])->name('lessons.create');
    Route::post('/lessons', [LessonManagementController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{lesson}/edit', [LessonManagementController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [LessonManagementController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [LessonManagementController::class, 'destroy'])->name('lessons.destroy');

    // Laporan Performa
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Rekomendasi AI
    Route::get('/ai-recommendations', [AiRecommendationController::class, 'index'])->name('ai-recommendations.index');
    Route::post('/ai-recommendations/{aiRecommendation}/approve', [AiRecommendationController::class, 'approve'])->name('ai-recommendations.approve');
    Route::post('/ai-recommendations/{aiRecommendation}/reject', [AiRecommendationController::class, 'reject'])->name('ai-recommendations.reject');

    // Manajemen Pengguna
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle', [UserManagementController::class, 'toggle'])->name('users.toggle');
});
