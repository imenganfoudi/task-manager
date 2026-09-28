<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/projects');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect('/projects');
    })->name('dashboard');

    Route::get('/projects', function () {
        return view('projects');
    });

    Route::get('/projects/{project}', function (\App\Models\Project $project) {
        return view('project-tasks', ['project' => $project]);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';