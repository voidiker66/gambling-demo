<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssociatesController;
use App\Http\Controllers\AssociatesImportController;

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

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::post('/import/associates', [AssociatesImportController::class, 'fileImport'])->name('import.associates');
Route::get('/associates/list', [AssociatesController::class, 'list'])->name('associates.list');
