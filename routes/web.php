<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/projects', function () {
    return view('projects');
})->middleware('auth');

Route::get('/login-as-admin', function () {
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'admin@task.com'],
        ['name' => 'Admin', 'password' => bcrypt('password123')]
    );
    auth()->login($user);
    return redirect('/projects');
});