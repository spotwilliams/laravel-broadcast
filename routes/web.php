<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Notes;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::group(['middleware' => 'auth'], function () {
    Route::get('/notes', [Notes\IndexController::class, 'index'])->name('index_note');
    Route::get('/notes/create', [Notes\CreateController::class, 'create'])->name('create_note');
    Route::post('/notes/create', [Notes\CreateController::class, 'store'])->name('store_note');
    Route::get('/notes/{note}/edit', [Notes\EditController::class, 'edit'])->name('edit_note');
    Route::put('/notes/{note}', [Notes\EditController::class, 'update'])->name('update_note');
});

