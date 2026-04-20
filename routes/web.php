<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Shared Routes
Route::middleware(['auth', 'role:admin,company_owner'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Job Applications
    Route::resource('job_application', JobApplicationController::class);
    Route::put('job_application/{id}/restore', [JobApplicationController::class, 'restore'])->name('job_application.restore');

    // Job Vacancies
    Route::resource('job_vacancy', JobVacancyController::class);
    Route::put('job_vacancy/{id}/restore', [JobVacancyController::class, 'restore'])->name('job_vacancy.restore');

});

// Company Routes
Route::middleware(['auth', 'role:company_owner'])->group(function () {
    Route::get('my-company', [CompanyController::class, 'show'])->name('my-company.show');
    Route::get('my-company/edit/', [CompanyController::class, 'edit'])->name('my-company.edit');
    Route::put('my-company', [CompanyController::class, 'update'])->name('my-company.update');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Users
    Route::resource('user', UserController::class);
    Route::put('user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');

    // Companies
    Route::resource('company', CompanyController::class);
    Route::put('company/{id}/restore', [CompanyController::class, 'restore'])->name('company.restore');

    // Job Categories
    Route::resource('job_category', JobCategoryController::class);
    Route::put('job_category/{id}/restore', [JobCategoryController::class, 'restore'])->name('job_category.restore');
});

require __DIR__.'/auth.php';
