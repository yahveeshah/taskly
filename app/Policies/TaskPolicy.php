<?php
namespace App\Policies;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id || $this->managesTask($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id || $this->managesTask($user, $task);
    }

    private function managesTask(User $user, Task $task): bool
    {
        return $user->isManager()
            && $user->team_id !== null
            && $task->user?->team_id === $user->team_id;
    }
}
