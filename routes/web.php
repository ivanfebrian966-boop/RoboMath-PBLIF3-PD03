<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ParentDashboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AlgoKids Web Routes
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
Route::middleware(['auth', RoleMiddleware::class . ':siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/achievements', [StudentDashboardController::class, 'achievements'])->name('achievements');

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

    // Chatbot
    Route::post('/chat/send', [ChatbotController::class, 'send'])->name('chat.send');
    Route::get('/chat/history', [ChatbotController::class, 'history'])->name('chat.history');
});

// ============================================================
// GURU Routes
// ============================================================
Route::middleware(['auth', RoleMiddleware::class . ':guru,admin'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/students/{student}', [TeacherDashboardController::class, 'studentDetail'])->name('students.show');
});

// ============================================================
// ORANGTUA Routes
// ============================================================
Route::middleware(['auth', RoleMiddleware::class . ':orangtua'])->prefix('orangtua')->name('orangtua.')->group(function () {
    Route::get('/dashboard', [ParentDashboardController::class, 'dashboard'])->name('dashboard');
});

// ============================================================
// ADMIN Routes
// ============================================================
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});
