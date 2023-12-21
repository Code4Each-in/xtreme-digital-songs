<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/', [AuthController::class, 'index']);
Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');

//Authenticated Group Routes Starts
Route::group(['middleware' => ['auth']], function() {
Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
Route::get('/companies', function () {
    return view('users.index');
});
Route::post('/company/add/',[CompaniesController::class,'store']);
Route::get('logout', [AuthController::class, 'logOut'])->name('logout');
});
//Authenticated Group Routes Ends
