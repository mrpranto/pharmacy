<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
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
    //Initial routes.
   Route::get('home', [DashboardController::class, 'home']);
   Route::get('lang/{lang}', [DashboardController::class, 'setLang'])->name('set-lang');
   Route::get('color-mode/{mode}', [DashboardController::class, 'setColorMode'])->name('set-color-mode');

   //Logout route.
   Route::post('logout', [AuthController::class, 'logout'])->name('logout');

   //Settings route.
   Route::get('setting', [SettingsController::class, 'getSetting'])->name('setting');
   Route::post('setting', [SettingsController::class, 'storeSetting']);

   //Roles route.
   Route::resource('roles', RoleController::class);
   Route::get('get-roles', [RoleController::class, 'getRoles']);
   Route::get('get-permissions', [RoleController::class, 'getPermissions']);

   //Users route.
   Route::resource('users', UserController::class);
   Route::get('get-users', [UserController::class, 'getUsers']);
   Route::get('get-roles-for-users', [UserController::class, 'getRoles']);

   Route::get('test', function (){
      return view('vue-test');
   });

});
