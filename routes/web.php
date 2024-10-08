<?php

use App\Http\Controllers\api\APIController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\ApprovalController; // Tambahkan ini
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
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
    Route::get('/menu', [MenuController::class, 'index']);
    Route::get('/menu/superadmin',[MenuController::class,'superadmin'])->middleware('userAksesMenu:superadmin');
    Route::get('/menu/admin',[MenuController::class,'admin'])->middleware('userAksesMenu:admin');
    Route::get('/menu/sechead',[MenuController::class,'sechead'])->middleware('userAksesMenu:section_head');
    Route::get('/menu/dephead',[MenuController::class,'dephead'])->middleware('userAksesMenu:department_head');
    Route::get('/menu/divhead',[MenuController::class,'divhead'])->middleware('userAksesMenu:division_head');
    Route::get('/menu/hrd',[MenuController::class,'hrd'])->middleware('userAksesMenu:hrd');
    Route::get('/menu/logout',[AuthController::class,'logout']); 
});
Route::get('logout',[AuthController::class,'logout'])->name('logout'); 

// Karyawan
Route::get('/karyawan',[KaryawanController::class,'index'])->name('karyawan.index'); 
Route::post('/karyawan',[KaryawanController::class,'store']);
Route::get('/karyawan/detail/{npk}', [KaryawanController::class, 'detail']);
Route::get('/karyawan/edit/{npk}', [KaryawanController::class, 'edit']);
Route::put('/karyawan/update/{npk}', [KaryawanController::class, 'update']);
Route::delete('/karyawan/delete/{npk}', [KaryawanController::class, 'destroy']);

// Overtime Plan
Route::get('/overtime',[OvertimeController::class,'index'])->name('overtime.index'); 
Route::get('/overtime/planning',[OvertimeController::class,'planning'])->name('overtime.planning');
Route::post('/overtime/store', [OvertimeController::class, 'store'])->name('overtime.store'); // Route untuk menyimpan data dari form
Route::delete('/overtime/{id}', [OvertimeController::class, 'destroy'])->name('overtime.destroy');
Route::get('/overtime/{id}', [OvertimeController::class, 'show'])->name('overtime.show');
Route::get('/overtime/edit/{id}', [OvertimeController::class, 'edit'])->name('overtime.edit');
Route::put('/overtime/update/{id}', [OvertimeController::class, 'update'])->name('overtime.update');

// Approval
Route::middleware(['auth'])->group(function () {
    // Rute untuk approval planning
    Route::get('/approval/planning', [ApprovalController::class, 'showPlanningApproval'])->name('approval.planning');
    Route::post('/approval/planning/approve/{id}', [ApprovalController::class, 'approvePlanning'])->name('approval.planning.approve');


    // Rute untuk approval actual
    Route::get('/approval/actual', [ApprovalController::class, 'showActualApproval'])->name('approval.actual');
    Route::post('/approval/actual/{id}', [ApprovalController::class, 'approveActual'])->name('approval.actual.approve');
});
