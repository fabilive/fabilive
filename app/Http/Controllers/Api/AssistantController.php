<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AI\MultilingualAssistantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AssistantController extends Controller
{
    protected $assistantService;

    public function __construct(MultilingualAssistantService $assistantService)
    {
        $this->assistantService = $assistantService;
        // Limit total requests to assistant endpoint
        // Handled by AIService internally, but we can add standard auth middleware if needed
    }

    /**
     * Send a message to the AI assistant.
     */
    public function reply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array|max:20',
            'history.*.role' => 'required|in:user,assistant',
            'history.*.content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $userId = auth()->id() ?? 0; // 0 for guest if allowed, though rate limits track by 0
        $message = $request->message;
        $history = $request->history ?? [];

        try {
            $response = $this->assistantService->reply($userId, $message, $history);

            if (isset($response['error'])) {
                return response()->json([
                    'success' => false,
                    'message' => $response['error'],
                    'fallback' => $response['fallback'] ?? false
                ], 429); // or 400
            }

            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        } catch (\Exception $e) {
            Log::error("Assistant Controller Exception: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while connecting to the Fabilive Assistant.',
                'fallback' => true
            ], 500);
        }
    }
}
