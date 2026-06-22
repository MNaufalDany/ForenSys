<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaseController;
use App\Models\AutopsyCase;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Default
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', function () {
    $cases = AutopsyCase::with(['assignedDoctor', 'registrationOfficer'])
        ->latest()
        ->paginate(5);

    return view('dashboard.index', [
        'totalCases' => AutopsyCase::count(),
        'registeredCases' => AutopsyCase::where('status', 'Registered')->count(),
        'inProgressCases' => AutopsyCase::where('status', 'In Progress')->count(),
        'completedCases' => AutopsyCase::where('status', 'Completed')->count(),
        'cases' => $cases,
    ]);
})->name('dashboard');

// Case Management
Route::get('/cases', [CaseController::class, 'index'])->name('cases.index');
Route::get('/cases/create', [CaseController::class, 'create'])->name('cases.create');
Route::post('/cases', [CaseController::class, 'store'])->name('cases.store');
Route::get('/cases/{id}', [CaseController::class, 'show'])->name('cases.show');


Route::get('/examination', [CaseController::class, 'examinationIndex'])->name('examination.index');
Route::get('/cases/{id}/examination', [CaseController::class, 'examination'])->name('cases.examination');
Route::post('/cases/{id}/examination', [CaseController::class, 'saveExamination'])->name('cases.examination.save');


Route::get('/cases/{id}/edit', [CaseController::class, 'edit'])->name('cases.edit');
Route::put('/cases/{id}', [CaseController::class, 'update'])->name('cases.update');

Route::put('/cases/{id}/verify', [CaseController::class, 'verifyCase'])->name('cases.verify');
Route::put('/cases/{id}/close', [CaseController::class, 'closeCase'])->name('cases.close');


// Other Pages
Route::get('/registration', function () {
    return view('registration.index');
})->name('registration.index');

// Route::get('/examination', function () {
//     return view('examination.index');
// })->name('examination.index');

Route::get('/reports', function () {
    return view('reports.index');
})->name('reports.index');

Route::get('/users', function () {
    return view('users.index');
})->name('users.index');

Route::get('/profile', function () {
    return view('profile.index');
})->name('profile.index');
