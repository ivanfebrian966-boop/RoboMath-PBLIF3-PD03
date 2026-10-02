<?php

namespace App\AI;

use App\AI\Contracts\AIProviderInterface;
use App\AI\DTOs\AIChatResponse;
use App\AI\Providers\GeminiProvider;
use App\AI\Providers\LMStudioProvider;
use App\AI\Providers\MockProvider;
use App\Models\User;
use InvalidArgumentException;

class AIManager
{
    /**
     * Resolve the active AI provider instance.
     */
    public function driver(?string $driver = null): AIProviderInterface
    {
        $driver = $driver ?? config('services.ai.provider', env('AI_PROVIDER', 'mock'));

        return match (strtolower($driver)) {
            'gemini' => new GeminiProvider,
            'lmstudio', 'local' => new LMStudioProvider,
            'mock' => new MockProvider,
            default => throw new InvalidArgumentException("Unsupported AI driver: [{$driver}]"),
        };
    }

    /**
     * Helper shortcut to send chat message using configured provider.
     */
    public function chat(string $message, ?User $user = null, ?string $context = null, ?string $driver = null): AIChatResponse
    {
        return $this->driver($driver)->chat($message, $user, $context);
    }
}
