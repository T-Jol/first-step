<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('test');
});

// Instead of using Sanctum (or something similar) for API authentication,
// we are using the built-in Laravel session authentication system.
require_once __DIR__ . '/api.php';

// Catch-all route pour le frontend SPA (Vue.js)
Route::get('/{any}', function () {
    return view('welcome'); // ou la vue qui charge ton app Vue
})->where('any', '^(?!api).*$');