<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\CompanyController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\UnitController;
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
   Route::post('setting-backup', [SettingsController::class, 'backup'])->name('setting.backup');

   //Roles route.
   Route::resource('roles', RoleController::class);
   Route::get('get-roles', [RoleController::class, 'getRoles']);
   Route::get('get-permissions', [RoleController::class, 'getPermissions']);

   //Users route.
   Route::resource('users', UserController::class);
   Route::get('get-users', [UserController::class, 'getUsers']);
   Route::get('get-roles-for-users', [UserController::class, 'getRoles']);

   //Products route
    Route::group(['prefix' => 'product'], function (){
        Route::resource('products', ProductController::class);

        Route::resource('categories', CategoryController::class);
        Route::get('get-categories', [CategoryController::class, 'getCategories']);

        Route::resource('companies', CompanyController::class);
        Route::get('get-companies', [CompanyController::class, 'getCompanies']);

        Route::resource('units', UnitController::class);
        Route::get('get-units', [UnitController::class, 'getUnits']);
    });

   Route::get('test', function (){
      $files =  \Illuminate\Support\Facades\Storage::allFiles('pharmacy-management/db-backup/');


       $file = $files[0];
       $disk = \Illuminate\Support\Facades\Storage::disk(config('backup.backup.destination.disks')[0]);
       if ($disk->exists($file)) {
           $fs = \Illuminate\Support\Facades\Storage::disk(config('backup.backup.destination.disks')[0])->getDriver();
           $stream = $fs->readStream($file);
           return \Response::stream(function () use ($stream) {
               fpassthru($stream);
           }, 200, [
               "Content-Type" => $fs->mimeType($file),
               "Content-Length" => $fs->fileSize($file),
               "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
           ]);
       }

//      return view('vue-test',['files' => $files]);
   });

   Route::get('get-backup', function (){
      \Illuminate\Support\Facades\Artisan::call('backup:run --only-db');

      return true;
   });

});
