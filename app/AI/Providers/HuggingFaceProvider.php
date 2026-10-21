<?php

namespace App\AI\Providers;

use App\AI\Contracts\AIProviderInterface;
use App\AI\DTOs\AIChatResponse;
use App\AI\Prompts\RoboBotPrompt;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HuggingFaceProvider implements AIProviderInterface
{
    protected string $apiKey;

    protected string $model;

    protected string $baseUrl;

    public function __construct(?string $apiKey = null, ?string $model = null, ?string $baseUrl = null)
    {
        $this->apiKey = $apiKey ?? config('services.huggingface.api_key', env('HUGGINGFACE_API_KEY', ''));
        $this->model = $model ?? config('services.huggingface.model', env('HUGGINGFACE_MODEL', 'meta-llama/Llama-3.1-8B-Instruct'));
        $this->baseUrl = $baseUrl ?? config('services.huggingface.base_url', env('HUGGINGFACE_BASE_URL', 'https://router.huggingface.co/v1'));
    }

    public function chat(string $message, ?User $user = null, ?string $context = null): AIChatResponse
    {
        if (empty($this->apiKey)) {
            Log::warning('Hugging Face API key is not configured, falling back to Gemini/Mock.');

            return $this->fallback($message, $user, $context);
        }

        $systemInstruction = match ($context) {
            'json' => 'You are an expert educational assessment specialist and mathematics teacher. You MUST always output valid pure JSON strictly following the requested structure, with no markdown code blocks and no surrounding text.',
            'raw' => 'You are an expert AI document summarizer and education assistant for RoboMath. Follow all formatting and summarization guidelines provided in the prompt accurately in Indonesian.',
            default => RoboBotPrompt::systemPrompt($user, $context),
        };

        $payload = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemInstruction,
                ],
                [
                    'role' => 'user',
                    'content' => $message,
                ],
            ],
            'temperature' => $context === 'json' ? 0.2 : 0.4,
            'max_tokens' => $context === 'raw' ? 4000 : 1500,
        ];

        if ($context === 'json') {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        try {
            $endpoint = rtrim($this->baseUrl, '/').'/chat/completions';
            $timeout = $context === 'raw' ? 120 : 60;

            $response = Http::withToken($this->apiKey)
                ->timeout($timeout)
                ->post($endpoint, $payload);

            if ($response->successful()) {
                $rawContent = $response->json('choices.0.message.content') ?? '';

                // Clean any reasoning <think>...</think> tags if present
                $cleanText = preg_replace('/<think>[\s\S]*?<\/think>/i', '', $rawContent);
                $cleanText = trim($cleanText);

                $tokensUsed = $response->json('usage.total_tokens');

                return new AIChatResponse(
                    text: $cleanText ?: $rawContent,
                    provider: 'huggingface',
                    tokensUsed: $tokensUsed
                );
            }

            Log::error("Hugging Face API Error ({$response->status()}): ".$response->body());
        } catch (\Throwable $e) {
            Log::error('Hugging Face Provider Exception: '.$e->getMessage());
        }

        return $this->fallback($message, $user, $context);
    }

    /**
     * Fallback to OpenAI if configured, otherwise Mock.
     */
    protected function fallback(string $message, ?User $user = null, ?string $context = null): AIChatResponse
    {
        $openAiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));
        if (! empty($openAiKey)) {
            try {
                return (new OpenAIProvider)->chat($message, $user, $context);
            } catch (\Throwable $e) {
                Log::error('Hugging Face fallback to OpenAI failed: '.$e->getMessage());
            }
        }

        return (new MockProvider)->chat($message, $user, $context);
    }
}
