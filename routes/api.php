<?php

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


// Pasar
Route::get('markets/{kabupaten_id}/{petugas_id}', [App\Http\Controllers\API\MarketController::class, 'pasar_petugas']);






Route::get('market/retribution/{id}', [App\Http\Controllers\API\ObligationRetributionController::class, 'getNamaPasar']);

// Wajib Retribusi
Route::get('obligation-retributions/user/{user_id}', [App\Http\Controllers\API\ObligationRetributionController::class, 'obligation_retribution_by_user']);


// API UNTUK MENDAPATKAN RETRIBUSI BULANAN


// API UNTUK MENAMPILKAN NO UNIT DENGAN HARGA DI PEMBAYARAN HARIAN

Route::get('units/retribution/{pasar_id}/{unit_id}', [App\Http\Controllers\API\UnitController::class, 'getDetailUnit']);

// -----
// -----
// -----
// -----


// API UNTUK EDIT PROFILE
Route::put('user/edit/{id}', [App\Http\Controllers\API\AuthController::class, 'updateUser']);

// API UNTUK LOGIN 
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

// API UNTUK MENDAFTARKAN KABUPATEN
// Route::post('/add-regency', [App\Http\Controllers\API\RegencyController::class, 'store']);

// API RESOURCE KABUPATEN
Route::resource('regencies', App\Http\Controllers\API\RegencyController::class);

// API RESOURCE PENGATURAN MENU
Route::resource('menu-settings', App\Http\Controllers\API\MenuSettingController::class);

// API RESOURCE KELOMPOK PASAR
Route::resource('market-groups', App\Http\Controllers\API\MarketGroupController::class);

// API RESOURCE PASAR
Route::resource('markets', App\Http\Controllers\API\MarketController::class);

// API UNTUK MENDAFTARKAN USER / PENGGUNA BARU
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);

// API RESOURCE PETUGAS PASAR
Route::resource('market-officers', App\Http\Controllers\API\MarketOfficerController::class);

// API RESOURCE WAJIB RETRIBUSI
Route::resource('obligation-retributions', App\Http\Controllers\API\ObligationRetributionController::class);

// API RESOURCE JENIS UNIT / TEMPAT
Route::resource('unit-types', App\Http\Controllers\API\UnitTypeController::class);

// API RESOURCE UNIT
Route::resource('units', App\Http\Controllers\API\UnitController::class);

// API RESOURCE BIAYA RETRIBUSI
Route::resource('retribution-fees', App\Http\Controllers\API\RetributionFeeController::class);

// API UNTUK HALAMAN BUKTI BAYAR SETORAN HARIAN
Route::get('units/retribution/{kabupaten_id}', [App\Http\Controllers\API\UnitController::class, 'getUnit']);

// API UNTUK MENAMPILKAN LIST CONTRACT BERDASARKAN USER ID
Route::get('contracts/show/{user_id}', [App\Http\Controllers\API\ContractController::class, 'getContractByUser']);

// API RESOURCE SURAT SEWA MENYEWA
Route::resource('contracts', App\Http\Controllers\API\ContractController::class);

// API UNTUK MENAMPILKAN RETRIBUSI NON HARIAN
Route::get('mandatory-retributions/invoice', [App\Http\Controllers\API\MandatoryRetributionController::class, 'getBulanan']);

// API RESOURCE RETRIBUSI WAJIB BAYAR
Route::resource('mandatory-retributions', App\Http\Controllers\API\MandatoryRetributionController::class);

// API RESOURCE RETRIBUSI HARIAN DAN OTOMATIS MENAMBAKAN SETORAN HARIAN
Route::post('/daily-retributions/{user_id}/{pasar_id}', [App\Http\Controllers\API\DailyRetributionController::class, 'uploadSetoranRetribusi']);

// API UNTUK MENDAPATKAN LIST DAFTAR BUKTI BAYAR
Route::get('/daily-retributions/list', [App\Http\Controllers\API\DailyRetributionController::class, 'daftarBuktiBayar']);

// API UNTUK MENDAPATKAN LIST UNGGAH BUKTI BAYAR
Route::get('deposits/list/{user_id}/{pasar_id}', [App\Http\Controllers\API\DepositController::class, 'unggahBuktiBayar']);

// API UNTUK MENAMPILKAN LIST SETORAN BERDASARKAN PETUGAS YANG LOGIN DAN ID PASAR
Route::get('deposits/{user_id}/{pasar_id}', [App\Http\Controllers\API\DepositController::class, 'getSpesificDeposit']);

// API RESOURCE PENYETORAN
Route::resource('deposits', App\Http\Controllers\API\DepositController::class);

// API UNTUK MENGUPDATE STATUS PEMBAYARAN NON HARIAN
Route::post('update-mandatory-retributions', [App\Http\Controllers\API\MandatoryRetributionController::class, 'updateStatusPembayaran']);

// API UNTUK USER DETAIL
Route::get('user/{id}', [App\Http\Controllers\API\AuthController::class, 'userDetail']);

// API UNTUK PAYMENT GATEWAY
Route::post('/payment', [App\Http\Controllers\API\MandatoryRetributionController::class, 'cashlessPayment']);

// API UNTUK PAYMENT GATEWAY QRIS
Route::post('/payment/qris', [App\Http\Controllers\API\MandatoryRetributionController::class, 'cashlessPaymentQris']);

// API UNTUK MENAMPILKAN TAGIHAN NON HARIAN WAJIB RETRIBUSI
Route::get('/retribution-wr/{user_id}', [App\Http\Controllers\API\MandatoryRetributionController::class, 'getRetribusiWr']);

// API UNTUK CALLBACK HTTP NOTIFICATIONS
Route::post('/after-payments', [App\Http\Controllers\API\MandatoryRetributionController::class, 'notifications']);

// API UNTUK HALAMAN PILIH PASAR MENDAPATKAN DAFTAR PASAR YANG DI ASSIGN KE PETUGAS
Route::get('choose-markets/{officer_id}', [App\Http\Controllers\API\MarketController::class, 'choose_market']);

Route::resource('/daily-retributions', App\Http\Controllers\API\DailyRetributionController::class);

// API UNTUK RIWAYAT PEMBAYARAN
Route::get('/history-payment/{user_id}', [App\Http\Controllers\API\MandatoryRetributionController::class, 'riwayatPembayaran']);

Route::middleware('auth:sanctum')->group(function () {


    // Logout
    Route::get('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);

    
});