<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvaluateController;

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

Route::post('/evaluate/add', [EvaluateController::class, 'addProduct']);
Route::post('/evaluate/del', [EvaluateController::class, 'delProduct']);
Route::post('/evaluate/edit', [EvaluateController::class, 'editProduct']);
Route::get('/evaluate/list', [EvaluateController::class, 'getProductList']);
