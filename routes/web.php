<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/projects');
});

Route::middleware('auth')->group(function () {
    Route::get('/projects', function () {
        return view('projects');
    });

    Route::get('/projects/{project}', function (\App\Models\Project $project) {
        return view('project-tasks', ['project' => $project]);
    });
});

require __DIR__.'/auth.php';