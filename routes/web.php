<?php

use App\Http\Controllers\Admin\AiRecommendationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DocumentSummarizerController;
use App\Http\Controllers\Admin\LessonManagementController;
use App\Http\Controllers\Admin\QuizManagementController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SekolahController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\TugasController;
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
    Route::post('/kelas/preview', [ClassRoomController::class, 'previewJoin'])->middleware('throttle:10,1')->name('kelas.join.preview');
    Route::post('/kelas/join', [ClassRoomController::class, 'join'])->name('kelas.join.store');

    // Tugas (siswa)
    Route::get('/tugas', [TugasController::class, 'tugasSiswa'])->name('tugas.index');
    Route::post('/tugas/{tugas}/kumpulkan', [TugasController::class, 'kumpulkanTugas'])->name('tugas.kumpulkan');
});

// ============================================================
// GURU Routes
// ============================================================
Route::middleware(['auth', RoleMiddleware::class.':guru,admin'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/students/{student}', [TeacherDashboardController::class, 'studentDetail'])->name('students.show');
    Route::get('/laporan/download', [TeacherDashboardController::class, 'downloadReport'])->name('laporan.download');
    Route::get('/laporan/download/pdf', [TeacherDashboardController::class, 'downloadReportPdf'])->name('laporan.pdf');

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'teacherLeaderboard'])->name('leaderboard');

    // Manajemen Kelas
    Route::get('/kelas', [ClassRoomController::class, 'index'])->name('classes.index');
    Route::get('/kelas/buat', [ClassRoomController::class, 'create'])->name('classes.create');
    Route::post('/kelas', [ClassRoomController::class, 'store'])->name('classes.store');
    Route::get('/kelas/{classRoom}', [ClassRoomController::class, 'show'])->name('classes.show');
    Route::delete('/kelas/{classRoom}', [ClassRoomController::class, 'destroy'])->name('classes.destroy');

    // Tugas (guru)
    Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
    Route::get('/tugas/buat', [TugasController::class, 'create'])->name('tugas.create');
    Route::post('/tugas', [TugasController::class, 'store'])->name('tugas.store');
    Route::get('/tugas/{tugas}', [TugasController::class, 'show'])->name('tugas.show');
    Route::delete('/tugas/{tugas}', [TugasController::class, 'destroy'])->name('tugas.destroy');
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
    Route::post('/quizzes/generate-ai', [QuizManagementController::class, 'generateAi'])->name('quizzes.generate-ai');
    Route::post('/quizzes/save-batch', [QuizManagementController::class, 'saveBatch'])->name('quizzes.save-batch');
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

    // Rekomendasi & Review AI
    Route::get('/ai-recommendations', [AiRecommendationController::class, 'index'])->name('ai-recommendations.index');
    Route::post('/ai-recommendations/{aiRecommendation}/approve', [AiRecommendationController::class, 'approve'])->name('ai-recommendations.approve');
    Route::post('/ai-recommendations/{aiRecommendation}/reject', [AiRecommendationController::class, 'reject'])->name('ai-recommendations.reject');
    Route::post('/ai-recommendations/quizzes/{quiz}/approve', [AiRecommendationController::class, 'approveQuiz'])->name('ai-recommendations.quizzes.approve');
    Route::post('/ai-recommendations/quizzes/{quiz}/reject', [AiRecommendationController::class, 'rejectQuiz'])->name('ai-recommendations.quizzes.reject');
    Route::post('/ai-recommendations/topics/{topic}/approve', [AiRecommendationController::class, 'approveTopic'])->name('ai-recommendations.topics.approve');
    Route::post('/ai-recommendations/topics/{topic}/reject', [AiRecommendationController::class, 'rejectTopic'])->name('ai-recommendations.topics.reject');
    Route::post('/ai-recommendations/topics/{topic}/approve-all', [AiRecommendationController::class, 'approveTopicQuizzes'])->name('ai-recommendations.topics.approve-all');
    Route::post('/ai-recommendations/topics/{topic}/reject-all', [AiRecommendationController::class, 'rejectTopicQuizzes'])->name('ai-recommendations.topics.reject-all');

    // Manajemen Pengguna
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle', [UserManagementController::class, 'toggle'])->name('users.toggle');

    // Manajemen Sekolah
    Route::resource('/sekolah', SekolahController::class)->except(['show']);
});

// ============================================================
// RINGKASAN DOKUMEN AI (Admin & Guru)
// ============================================================
Route::middleware(['auth', RoleMiddleware::class.':guru,admin'])->prefix('admin/document-summarizer')->name('admin.document-summarizer.')->group(function () {
    Route::get('/', [DocumentSummarizerController::class, 'index'])->name('index');
    Route::post('/summarize', [DocumentSummarizerController::class, 'summarize'])->name('summarize');
    Route::post('/publish-to-students', [DocumentSummarizerController::class, 'publishToStudents'])->name('publish-to-students');
    Route::get('/{summary}', [DocumentSummarizerController::class, 'show'])->name('show');
    Route::delete('/{summary}', [DocumentSummarizerController::class, 'destroy'])->name('destroy');
    Route::get('/{summary}/download', [DocumentSummarizerController::class, 'downloadText'])->name('download');
});

Route::redirect('/ai-studio', '/admin/document-summarizer');

// ============================================================
// PROFIL — tersedia untuk semua peran yang sudah login
// ============================================================
Route::middleware('auth')->prefix('profil')->name('profil.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
});
