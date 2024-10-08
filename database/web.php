<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookkeepingController;
use App\Http\Controllers\OCRController;

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

Route::get('/demo', function () {
    // phpinfo();
    return view('welcome');
});

Route::get('/qrcode', function () {
    return view('qrcode');
});
Route::post('/extract-text', [OCRController::class, 'ocrImageProcess']);
$uri = explode('/', request()->path());
$path = $uri[0] == ''?request()->path():$uri[0];
$param = isset($uri[1])?$uri[1]:'';
// die($path);
Route::prefix('/')->group(function () use ($path, $param) {
    Route::middleware('bookkeeping.auth')->group(function () use ($path, $param) {
        Route::get('/', [BookkeepingController::class, 'redirect']);
        Route::any("$path/{param?}", [BookkeepingController::class, $path]);
    });
});
