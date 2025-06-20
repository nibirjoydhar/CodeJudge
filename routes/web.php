<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProblemListController;
use App\Http\Controllers\ContestController;
use Illuminate\Support\Facades\Route;

// Redirect the homepage to problems
Route::get('/', function () {
    return redirect()->route('problems.list');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('problems', ProblemController::class);
});

// Contestant Routes
Route::middleware(['auth', 'role:contestant'])->group(function () {
    Route::resource('submissions', SubmissionController::class)
        ->only(['index', 'create', 'store', 'show']);
});

// Comment Routes
Route::middleware('auth')->group(function () {
    Route::get('/problems/{problem}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/problems/{problem}/comments', [CommentController::class, 'store'])->name('comments.store');
});

// Leaderboard (accessible to all users)
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

// Problems List (accessible to authenticated users)
Route::middleware('auth')->get('/problems', [ProblemListController::class, 'index'])->name('problems.list');
Route::middleware('auth')->get('/problems/{problem}', [ProblemListController::class, 'show'])->name('problems.show');

// Contest routes
Route::middleware(['auth'])->group(function () {
    Route::get('/contests', [ContestController::class, 'index'])->name('contests.index');
    Route::get('/contests/create', [ContestController::class, 'create'])->name('contests.create');
    Route::post('/contests', [ContestController::class, 'store'])->name('contests.store');
    Route::get('/contests/{contest}', [ContestController::class, 'show'])->name('contests.show');
    Route::post('/contests/{contest}/join', [ContestController::class, 'join'])->name('contests.join');
    
    // Contest Problem Routes
    Route::get('/contests/{contest}/problems', [ContestController::class, 'problems'])
        ->name('contests.problems.index');
    Route::get('/contests/{contest}/problems/{problem}', [ContestController::class, 'showProblem'])
        ->name('contests.problems.show');
    Route::get('/contests/{contest}/problems/{problem}/submit', [ContestController::class, 'showSubmitForm'])
        ->name('contests.problems.submit');
    Route::post('/contests/{contest}/problems/{problem}/submit', [ContestController::class, 'submitSolution'])
        ->name('contests.problems.submit.store');
        
    // Contest Submissions Route
    Route::get('/contests/{contest}/submissions', [ContestController::class, 'submissions'])
        ->name('contests.submissions');
        
    // Contest Standings Route
    Route::get('/contests/{contest}/standings', [ContestController::class, 'standings'])
        ->name('contests.standings');
        
    // Contest Edit Route (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/contests/{contest}/edit', [ContestController::class, 'edit'])
            ->name('contests.edit');
        Route::put('/contests/{contest}', [ContestController::class, 'update'])
            ->name('contests.update');
    });
});

require __DIR__.'/auth.php';
