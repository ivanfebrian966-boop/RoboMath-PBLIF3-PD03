<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Topic;
use App\Services\MathAIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MathAIStudioController extends Controller
{
    public function __construct(protected MathAIService $mathAIService) {}

    /**
     * Menampilkan Halaman Utama AI Question Studio.
     */
    public function index()
    {
        $dataset = $this->mathAIService->getDataset();

        // Hitung statistik dataset
        $totalQuestions = count($dataset);
        $gradeDistribution = collect($dataset)->groupBy('grade')->map->count();
        $cognitiveDistribution = collect($dataset)->groupBy('labels.cognitive_level')->map->count();

        $topics = Topic::where('is_active', true)->orderBy('kelas_level')->get();

        return view('ai-studio.index', compact(
            'dataset',
            'totalQuestions',
            'gradeDistribution',
            'cognitiveDistribution',
            'topics'
        ));
    }

    /**
     * Endpoint API: Menganalisis tingkat kognitif soal (Classifier / Analyzer).
     */
    public function analyze(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question_text' => 'required|string|min:5',
            'grade' => 'required|integer|between:1,6',
        ]);

        $result = $this->mathAIService->analyzeQuestion(
            $validated['question_text'],
            (int) $validated['grade']
        );

        return response()->json($result);
    }

    /**
     * Endpoint API: Men-generate soal baru berbasis kognitif (Question Generator).
     */
    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'grade' => 'required|integer|between:1,6',
            'material' => 'required|string',
            'cognitive_level' => 'required|string',
            'context_type' => 'nullable|string',
        ]);

        $result = $this->mathAIService->generateQuestion(
            (int) $validated['grade'],
            $validated['material'],
            $validated['cognitive_level'],
            $validated['context_type'] ?? 'Contextual'
        );

        return response()->json($result);
    }

    /**
     * Menyimpan soal yang digenerate AI langsung ke Bank Soal / Kuis.
     */
    public function saveToQuiz(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'correct_answer' => 'required|string',
            'explanation' => 'nullable|string',
            'difficulty' => 'required|in:mudah,sedang,sulit',
        ]);

        $quiz = Quiz::create([
            'topic_id' => $validated['topic_id'],
            'type' => 'pilihan_ganda',
            'question' => $validated['question'],
            'options' => $validated['options'],
            'correct_answer' => $validated['correct_answer'],
            'explanation' => $validated['explanation'] ?? null,
            'difficulty' => $validated['difficulty'],
            'points' => 10,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil disimpan ke Bank Soal!',
            'quiz_id' => $quiz->id,
        ]);
    }
}
