<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasarController;
use App\Http\Controllers\KelompokPasarController;
use App\Http\Controllers\JenisUnitController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\BiayaRetribusiController;
use App\Http\Controllers\WajibRetribusiController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BendaharaController;

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

Route::get('/jenis-unit', [JenisUnitController::class, 'index']);
Route::post('/jenis-unit/store', [JenisUnitController::class, 'store']);
Route::post('/jenis-unit/update/{id}', [JenisUnitController::class, 'update']);
Route::get('/jenis-unit/delete/{id}', [JenisUnitController::class, 'destroy']);

Route::get('/unit', [UnitController::class, 'index']);
Route::post('/unit/store', [UnitController::class, 'store']);
Route::post('/unit/update/{id}', [UnitController::class, 'update']);
Route::get('/unit/delete/{id}', [UnitController::class, 'destroy']);

Route::get('/biaya-retribusi', [BiayaRetribusiController::class, 'index']);
Route::post('/biaya-retribusi/store', [BiayaRetribusiController::class, 'store']);
Route::post('/biaya-retribusi/update/{id}', [BiayaRetribusiController::class, 'update']);
Route::get('/biaya-retribusi/delete/{id}', [BiayaRetribusiController::class, 'destroy']);

Route::get('/wajib-retribusi', [WajibRetribusiController::class, 'index']);
Route::post('/wajib-retribusi/store', [WajibRetribusiController::class, 'store']);
Route::post('/wajib-retribusi/update/{id}', [WajibRetribusiController::class, 'update']);
Route::get('/wajib-retribusi/delete/{id}', [WajibRetribusiController::class, 'destroy']);

Route::get('/kontrak', [KontrakController::class, 'index']);
Route::post('/kontrak/store', [KontrakController::class, 'store']);
Route::post('/kontrak/update/{id}', [KontrakController::class, 'update']);
Route::get('/kontrak/delete/{id}', [KontrakController::class, 'destroy']);

Route::get('/users', [UserController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/update-user/{id}', [AuthController::class, 'updateUser']);
Route::get('/remove-user/{id}', [AuthController::class, 'removeUser']);

Route::get('/konfirmasi-setoran', [BendaharaController::class, 'confirmDeposit']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
