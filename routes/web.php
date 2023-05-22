<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasarController;
use App\Http\Controllers\KelompokPasarController;

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

Route::get('/login', [AuthController::class, 'displayLogin']);
Route::post('/login', [AuthController::class, 'loginCheck']);

Route::get('/', [HomeController::class, 'index']);
Route::get('/pasar', [PasarController::class, 'index']);
Route::post('/pasar/create', [PasarController::class, 'store']);
Route::get('/pasar/delete/{id}', [PasarController::class, 'destroy']);

Route::get('/kelompok-pasar', [KelompokPasarController::class, 'index']);
Route::post('/kelompok-pasar/store', [KelompokPasarController::class, 'store']);
Route::post('/kelompok-pasar/update/{id}', [KelompokPasarController::class, 'update']);
Route::get('/kelompok-pasar/delete/{id}', [KelompokPasarController::class, 'destroy']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
