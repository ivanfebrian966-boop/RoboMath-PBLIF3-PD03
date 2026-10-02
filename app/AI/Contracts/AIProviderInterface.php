<?php

namespace App\AI\Contracts;

use App\AI\DTOs\AIChatResponse;
use App\Models\User;

interface AIProviderInterface
{
    /**
     * Send a chat message and receive AI response.
     */
    public function chat(string $message, ?User $user = null, ?string $context = null): AIChatResponse;
}
