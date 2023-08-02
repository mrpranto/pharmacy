<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
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
    return redirect()->route('login');
});

Route::get('login', [AuthController::class, 'loginPage'])->name('login');
Route::post('login', [AuthController::class, 'processLogin']);

Route::group(['middleware' => 'authenticate'], function (){
   Route::get('home', [DashboardController::class, 'home']);
   Route::post('logout', [AuthController::class, 'logout'])->name('logout');

   Route::get('setting', [SettingsController::class, 'getSetting'])->name('setting');
   Route::post('setting', [SettingsController::class, 'storeSetting']);

   Route::resource('roles', RoleController::class);
   Route::get('get-roles', [RoleController::class, 'getRoles']);

   Route::get('test', function (){
      return view('vue-test');
   });

});
