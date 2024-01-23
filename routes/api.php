<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//rutas de autenticacion publicas
Route::post('user/register', [UserController::class, 'register'])->name('api.user.register');
Route::post('user/login', [UserController::class, 'login'])->name('api.user.login');

//books public
Route::get('book/index', [BookController::class, 'index'])->name('api.book.index');
Route::get('book/show/{id}', [BookController::class, 'show'])->name('api.book.show');
Route::get('book/export', [BookController::class, 'export'])->name('api.book.export');


Route::middleware(['auth:sanctum'])->group(function () {
    //rutas de autenticacion privadas
    Route::post('user/logout', [UserController::class, 'logout'])->name('api.user.logout');

    //books private
    Route::post('book/store', [BookController::class, 'store'])->name('api.book.store');
    Route::put('book/update/{id}', [BookController::class, 'update'])->name('api.book.update');
    Route::delete('book/delete/{id}', [BookController::class, 'destroy'])->name('api.book.delete');

});
