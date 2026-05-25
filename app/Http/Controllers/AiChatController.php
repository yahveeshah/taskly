<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiChatHistory;
use App\Services\AiChatService;
use Laravel\Ai\Exceptions\RateLimitedException;
use Laravel\Ai\Streaming\Events\TextDelta;

class AiChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $messages = AiChatHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->reverse();

        return view('ai.chat', compact('messages'));
    }

    public function store(Request $request, AiChatService $aiChatService)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $user = auth()->user();
        $message = $validated['message'];

        return response()->stream(function () use ($aiChatService, $user, $message) {
            $reply = '';

            try {
                foreach ($aiChatService->streamResponse($user, $message) as $event) {
                    if (! $event instanceof TextDelta) {
                        continue;
                    }

                    $reply .= $event->delta;
                    echo $event->delta;
                    $this->flushStream();
                }

                $aiChatService->persistConversation($user, $message, $reply);
            } catch (RateLimitedException $exception) {
    $reply = "I'm a little busy right now, please try again in a moment.";
    echo $reply;
    $this->flushStream();
} catch (\Throwable $exception) {
    file_put_contents(storage_path('logs/ai_debug.txt'), date('H:i:s') . ' ' . $exception->getMessage() . "\n", FILE_APPEND);
    $reply = "Something went wrong: " . $exception->getMessage();
    echo $reply;
    $this->flushStream();
}
        }, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-transform',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function destroy()
    {
        AiChatHistory::where('user_id', auth()->id())->delete();
        return back()->with('success', 'Chat history cleared.');
    }

    private function flushStream(): void
    {
        if (ob_get_level() > 0) {
            ob_flush();
        }

        flush();
    }
}
