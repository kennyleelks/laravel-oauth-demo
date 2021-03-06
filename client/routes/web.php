<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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

Route::get('/redirect', function (Request $request) {
    $query = http_build_query([
        'client_id' => env('OAUTH_CLIENT_ID'),
        'redirect_uri' => env('OAUTH_CALLBACK_URL'),
        'response_type' => 'code',
        'scope' => '',
    ]);

    return redirect(env('OAUTH_AUTHORIZE_URL') . '?' . $query);
});

Route::get('refresh', function (Request $request) {
    $response = Http::asForm()->post(env('OAUTH_GET_TOKEN_URL'), [
        'grant_type' => 'refresh_token',
        'refresh_token' => $request->token,
        'client_id' => env('OAUTH_CLIENT_ID'),
        'client_secret' => env('OAUTH_CLIENT_SECRET'),
        'scope' => '',
    ]);

    return $response->json();
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/avatar', [App\Http\Controllers\HomeController::class, 'avatar'])->name('avatar');
Route::get('/callback', [App\Http\Controllers\HomeController::class, 'callback'])->name('callback');
