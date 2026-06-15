<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvitationAcceptController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::middleware(['role:SuperAdmin,Admin,Member'])->group(function () {
        Route::get('/invitations-list', [InvitationController::class, 'index'])->name('invitations.index');
        Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
        Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
    });

    Route::middleware(['role:SuperAdmin'])->group(function () {
        Route::resource('companies', CompanyController::class);
        Route::get('/all-users-list', [UserController::class, 'index'])->name('users.index');
    });

    Route::get('/short-urls',[ShortUrlController::class, 'index'])->name('short-urls.index');
    Route::get('/short-urls/create',[ShortUrlController::class, 'create'])->name('short-urls.create');
    Route::post('/short-urls',[ShortUrlController::class, 'store'])->name('short-urls.store');

});


Route::get('/invite/{token}',[InvitationAcceptController::class, 'show'])->name('invite.accept');
Route::post('/invite/{token}', [InvitationAcceptController::class, 'store'] )->name('invite.store');

Route::get('/s/{code}', [ShortUrlController::class, 'redirect'])->name('short-urls.redirect');

require __DIR__.'/auth.php';
