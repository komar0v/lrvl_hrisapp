<?php

use App\Http\Controllers\Account\Account;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserCtrlr;
use App\Http\Controllers\Employee\Employee AS EmployeeCtrlr;
use App\Http\Controllers\Vacancy\ApplyJob;
use App\Http\Controllers\Vacancy\Job AS JobCtrlr;
use App\Http\Controllers\Applicants\Applicant AS ApplicantCtrlr;

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
Route::get('/vacancy/job/all', [JobCtrlr::class, 'showAllJobs']);
Route::get('/vacancy/job/{jobId}/detail', [JobCtrlr::class, 'jobDetails']);
Route::post('/vacancy/job/{jobId}/apply', [ApplyJob::class, 'applyJob']);
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
    Route::get('/employee/all', [EmployeeCtrlr::class, 'showAllEmployees']);
});

//--job_managements
Route::middleware(['auth:api', 'auth.role:superadmin'])->prefix('job_mgmts')->group(function () {
    Route::post('/job/create', [JobCtrlr::class, 'createNewJobOpenings']);
    Route::get('/job/all', [JobCtrlr::class, 'showAllJobs']);
});

//--job_managements
Route::middleware(['auth:api', 'auth.role:superadmin'])->prefix('applicant_mgmts')->group(function () {
    Route::get('/applicant/all', [ApplicantCtrlr::class, 'showAllApplicants']);
    Route::get('/applicant/{applicantId}/details', [ApplicantCtrlr::class, 'detailsApplicant']);
    Route::get('/applicant/{applicantId}/download_resume', [ApplicantCtrlr::class, 'downloadApplicantResume']);
    Route::post('/applicant/{applicantId}/manual_process', [ApplicantCtrlr::class, 'manualProcessApplicant']);
});
//END OF SUPERADMIN ROUTES
