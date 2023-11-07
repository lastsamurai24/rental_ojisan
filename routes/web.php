<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ojisan\OjisanLoginController;
use App\Http\Controllers\Ojisan\OjisanRegisteredController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'ojisan'], function () {
    // 登録
    Route::get('register', [OjisanRegisteredController::class, 'create'])
        ->name('ojisan.register');

    Route::post('register', [OjisanRegisteredController::class, 'store']);

    // ログイン
    Route::get('login', [OjisanLoginController::class, 'showLoginPage'])
        ->name('ojisan.login');

    Route::post('login', [OjisanLoginController::class, 'login']);

    // 以下の中は認証必須のエンドポイントとなる
    Route::middleware(['auth:ojisan'])->group(function () {
        // ダッシュボード
        Route::get('dashboard', fn() => view('ojisan.dashboard'))
            ->name('ojisan.dashboard');
    });
});
require __DIR__.'/auth.php';
