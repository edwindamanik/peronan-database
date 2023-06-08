<?php

use App\Http\Controllers\pengaturancontroller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RekonController;
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
Route::post('/pasar/update/{id}', [PasarController::class, 'update']);
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

Route::get('/{id}/kontrakpreview', [KontrakController::class, 'preview'])->name('kontrak.view');

Route::post('/kontrak/update/{id}', [KontrakController::class, 'update']);
Route::get('/kontrak/delete/{id}', [KontrakController::class, 'destroy']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/register', [AuthController::class, 'displayRegister']);
Route::post('/registerDinas', [AuthController::class, 'registerDinas'])->name('registerDinas');;
Route::get('/register-admin/{regencyID}', [AuthController::class, 'registerUserView'])->name('register-admin');
Route::post('/process-admin', [AuthController::class, 'registerUser']);
Route::post('/daftar', [AuthController::class, 'daftar']);
Route::post('/update-user/{id}', [AuthController::class, 'updateUser']);
Route::get('/remove-user/{id}', [AuthController::class, 'removeUser']);

Route::get('/pengaturan', [pengaturancontroller::class, 'index']);
Route::post('/pengaturan/update/{id}', [pengaturancontroller::class, 'update']);

Route::get('/konfirmasi-setoran', [BendaharaController::class, 'confirmDeposit']);
Route::post('/setor-deposit/{depositId}', [BendaharaController::class, 'setorDeposit'])->name('setor-deposit');
Route::post('/tolak-deposit/{depositId}', [BendaharaController::class, 'tolakdeposit'])->name('tolak-deposit');




Route::get('/laporansetor', [BendaharaController::class, 'lapsetor']);

Route::get('/setor/export_excel', [BendaharaController::class, 'export'])->name('export.setor');


Route::get('/batal/export_excel', [BendaharaController::class, 'exportbatal'])->name('export.batal');


Route::get('/tagihan', [BendaharaController::class, 'laptagihan']);

Route::get('/nonharian', [BendaharaController::class, 'retribusi']);

Route::get('/konfirmasipembatalan', [BendaharaController::class, 'konfirbatal']);
Route::get('/laporanpembatalan', [BendaharaController::class, 'laporbatal']);



Route::get('/rekon', [RekonController::class, 'rekon']);
Route::get('/rekondetail', [BendaharaController::class, 'rekondetail']);



Route::post('/batal/{batalId}', [BendaharaController::class, 'batalkan'])->name('batalkan-tagihan');
Route::post('/batalkan/{batalId}', [BendaharaController::class, 'batalkank'])->name('batalkan-kali');



Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
