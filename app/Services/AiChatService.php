<?php

namespace App\Services;

use App\Models\User;
use App\Models\AiChatHistory;
use Illuminate\Support\Str;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Responses\StreamableAgentResponse;

use function Laravel\Ai\agent;

class AiChatService
{
    public function getResponse(User $user, string $userMessage): string
    {
        $response = $this->agentFor($user, $userMessage)
            ->prompt($userMessage, provider: Lab::Gemini, model: 'gemini-2.0-flash');
        $this->persistConversation($user, $userMessage, $response->text);

        return $response->text;
    }

    public function streamResponse(User $user, string $userMessage): StreamableAgentResponse
    {
        return $this->agentFor($user, $userMessage)
            ->stream($userMessage, provider: Lab::Gemini, model: 'gemini-2.0-flash');
    }

    public function persistConversation(User $user, string $userMessage, string $assistantMessage): void
    {
        AiChatHistory::create(['user_id' => $user->id, 'role' => 'user', 'message' => $userMessage]);
        AiChatHistory::create(['user_id' => $user->id, 'role' => 'assistant', 'message' => $assistantMessage]);
    }

    private function agentFor(User $user, string $userMessage)
    {
        $context = $this->buildContext($user, $userMessage);
        $messages = $this->historyMessages($user);
        $systemPrompt = "You are a productivity assistant for Taskly. Help users manage their tasks effectively. Only answer questions related to task management, productivity, and team progress. Be concise.\n\nContext:\n" . $context;

        return agent(instructions: $systemPrompt, messages: $messages);
    }

    private function historyMessages(User $user): array
    {
        $history = AiChatHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->reverse();

        $messages = [];
        foreach ($history as $h) {
            if ($h->role === 'user') {
                $messages[] = new Message('user', $h->message);
            } else {
                $messages[] = new Message('assistant', $h->message);
            }
        }

        return $messages;
    }

    private function buildContext(User $user, string $userMessage): string
    {
        if ($user->role === 'manager' && $user->team_id) {
            // Check if specific member is mentioned
            $teamMembers = User::where('team_id', $user->team_id)->where('role', 'member')->get();
            $mentionedMember = null;
            
            foreach ($teamMembers as $member) {
                if (Str::contains(strtolower($userMessage), strtolower($member->name))) {
                    $mentionedMember = $member;
                    break;
                }
            }

            if ($mentionedMember) {
                $tasks = $mentionedMember->tasks()->get();
                $context = "Member {$mentionedMember->name} Tasks:\n";
                foreach ($tasks as $task) {
                    $context .= "- {$task->title} (Status: {$task->status}, Priority: {$task->priority}, Deadline: {$task->deadline})\n";
                }
                return $context;
            } else {
                $context = "Team Overview:\n";
                foreach ($teamMembers as $member) {
                    $total = $member->tasks()->count();
                    $completed = $member->tasks()->where('status', 'completed')->count();
                    $rate = $total > 0 ? round(($completed / $total) * 100) : 0;
                    $context .= "- {$member->name}: {$completed}/{$total} tasks completed ({$rate}%)\n";
                }
                return $context;
            }
        } else {
            $tasks = $user->tasks()->get();
            $context = "Your Tasks:\n";
            foreach ($tasks as $task) {
                $context .= "- {$task->title} (Status: {$task->status}, Priority: {$task->priority}, Deadline: {$task->deadline})\n";
            }
            return $context;
        }
    }
}
