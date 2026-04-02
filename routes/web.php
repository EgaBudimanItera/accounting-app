<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

use App\Http\Controllers\JournalController;

Route::get('/journal/create', [JournalController::class, 'create']);
Route::post('/journal/store', [JournalController::class, 'store']);

use App\Http\Controllers\ReportController;
// Route::get('/report/ledger/{id}', [ReportController::class, 'ledger']);
Route::get('/report/ledger', [ReportController::class, 'ledger']);
Route::get('/report/trial-balance', [ReportController::class, 'trialBalance']);
Route::get('/report/hutang', [ReportController::class, 'hutangPiutang']);

use App\Http\Controllers\AccountController;
Route::prefix('accounts')->group(function () {
    Route::get('/', [AccountController::class, 'index']);
    Route::get('/create', [AccountController::class, 'create']);
    Route::post('/store', [AccountController::class, 'store']);

    Route::get('/{id}/edit', [AccountController::class, 'edit']);
    Route::put('/{id}', [AccountController::class, 'update']);

    Route::delete('/{id}', [AccountController::class, 'destroy']);
});