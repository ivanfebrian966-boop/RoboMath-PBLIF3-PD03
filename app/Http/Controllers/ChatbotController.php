<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function send(Request $request, AIService $aiService)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'context' => 'nullable|string',
        ]);

        $user = Auth::user();
        $response = $aiService->chat($request->message, $user, $request->context);

        $chatMessage = ChatMessage::create([
            'user_id' => $user->id,
            'message' => $request->message,
            'response' => $response,
            'context' => $request->context,
        ]);

        return response()->json([
            'message' => $response,
            'timestamp' => $chatMessage->created_at->format('H:i'),
        ]);
    }

    public function history()
    {
        $user = Auth::user();
        $messages = ChatMessage::where('user_id', $user->id)
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json($messages);
    }
}
