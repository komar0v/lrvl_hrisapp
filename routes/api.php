<?php

use App\Http\Controllers\Account\Account;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserCtrlr;
use App\Http\Controllers\Employee\Employee AS EmployeeCtrlr;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//NO AUTH ROUTES
Route::post('/login', [Account::class, 'login']);
//END OF NO AUTH ROUTES

//UNIVERSAL ROUTES, ACCESSIBLE BY ALL ROLES
Route::middleware(['auth:api', 'auth.role:user,superadmin'])->group(function () {
    Route::get('/account/me', [Account::class, 'me']);
    Route::post('/account/logout', [Account::class, 'logout']);
    // Route::post('/account/change_password', [Users::class, 'gantiPassword']);
    // Route::post('/account/update', [Users::class, 'editUserData']);
});
//END OF UNIVERSAL ROUTES

//SUPERADMIN ROUTES
//--user_account
Route::middleware(['auth:api', 'auth.role:superadmin'])->prefix('user_account')->group(function () {
    Route::post('/user/register', [UserCtrlr::class, 'registerUser']);
    Route::get('/user/all', [UserCtrlr::class, 'getAllUsers']);
});

//--employee_managements
Route::middleware(['auth:api', 'auth.role:superadmin'])->prefix('emp_mgmts')->group(function () {
    Route::post('/employee/register', [EmployeeCtrlr::class, 'addNewEmployee']);
    Route::post('/employee/upload_bulk', [EmployeeCtrlr::class, 'importNewEmployeeFromFile']);
    // Route::get('/employee/all', [EmployeeCtrlr::class, 'getAllUsers']);
});
//END OF SUPERADMIN ROUTES
