<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;

Route::get('/', function () { return view('home'); });
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('dashboard');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::get('/tasks/history', [TaskController::class, 'history'])->name('tasks.history');
    Route::delete('/tasks/history/selected', [TaskController::class, 'destroySelected'])->name('tasks.history.selected');
    Route::delete('/tasks/history/all', [TaskController::class, 'destroyAll'])->name('tasks.history.all');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
    Route::get('/progress', [TaskController::class, 'progress'])->name('progress');
    Route::get('/graph', [TaskController::class, 'graph'])->name('graph');
    Route::get('/track', [TaskController::class, 'track'])->name('track');
    Route::get('/team', [TeamController::class, 'index'])->name('team.index');
    Route::get('/team/members/{user}', [TeamController::class, 'member'])->name('team.member');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/ai/chat', [\App\Http\Controllers\AiChatController::class, 'index'])->name('ai.chat.index');
    Route::post('/ai/chat', [\App\Http\Controllers\AiChatController::class, 'store'])->name('ai.chat.store');
    Route::delete('/ai/chat', [\App\Http\Controllers\AiChatController::class, 'destroy'])->name('ai.chat.destroy');
    
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/members', [\App\Http\Controllers\ChatController::class, 'getTeamMembers'])->name('chat.members');
    Route::delete('/chat/messages/{message}', [\App\Http\Controllers\ChatController::class, 'destroy'])->name('chat.messages.destroy');
    Route::delete('/chat/clear', [\App\Http\Controllers\ChatController::class, 'clearHistory'])->name('chat.clear');
});

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\ResetPasswordController::class, 'store'])->name('password.update');
});



