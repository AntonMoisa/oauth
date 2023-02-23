<?php

use App\Http\Controllers\OauthController;
use App\Http\Controllers\UserController;
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
})->name('index');

Route::get('/user', [UserController::class, 'index'])->name('user');
Route::get('/user/logout', [UserController::class, 'logout'])->name('user.logout');
Route::get('/user/address/{currency}', [UserController::class, 'address'])->name('user.address');
Route::get('/user/balance/{currency}', [UserController::class, 'balance'])->name('user.balance');
Route::post('/user/transfer', [UserController::class, 'transfer'])->name('user.transfer');

Route::get('oauth/login', [OauthController::class, 'login'])->name('oauth.login');
Route::get('oauth/callback', [OauthController::class, 'callback'])->name('oauth.callback');
