<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OvertimeController;
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

Route::middleware(['guest'])->group(function() {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/', [AuthController::class, 'login']);
});
Route::get('/home', function () {
    return redirect('/menu');
});

Route::middleware(['auth'])->group(function(){
    Route::get('/menu',[MenuController::class,'index'])->middleware('userAksesMenu:superadmin,admin,hrd');
    Route::get('/menu/superadmin',[MenuController::class,'superadmin'])->middleware('userAksesMenu:superadmin');
    Route::get('/menu/admin',[MenuController::class,'admin'])->middleware('userAksesMenu:admin');
    Route::get('/menu/sechead',[MenuController::class,'sechead'])->middleware('userAksesMenu:sechead');
    Route::get('/menu/dephead',[MenuController::class,'dephead'])->middleware('userAksesMenu:dephead');
    Route::get('/menu/divhead',[MenuController::class,'divhead'])->middleware('userAksesMenu:divhead');
    Route::get('/menu/hrd',[MenuController::class,'hrd'])->middleware('userAksesMenu:hrd');
    Route::get('/menu/logout',[AuthController::class,'logout']); 
});
Route::get('logout',[AuthController::class,'logout']); 

//karyawan
Route::get('/karyawan',[KaryawanController::class,'index']); 
Route::post('/karyawan',[KaryawanController::class,'store']);
Route::get('/karyawan/detail/{npk}', [KaryawanController::class, 'detail']);
Route::get('/karyawan/edit/{npk}', [KaryawanController::class, 'edit']);
Route::put('/karyawan/update/{npk}', [KaryawanController::class, 'update']);
Route::delete('/karyawan/delete/{npk}', [KaryawanController::class, 'destroy']);

//Overtime Plan
Route::get('/overtime',[OvertimeController::class,'index']); 
Route::get('/overtime/planning',[OvertimeController::class,'planning']); 
Route::post('/overtime/store', [OvertimeController::class, 'store'])->name('overtime.store');   // Route untuk menyimpan data dari form



