<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Mail\VerifyUserEmailMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::view('/auth/login', 'auth.login');
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

Route::resource('roles', RoleController::class)->except(['show']);
Route::get('users', [UserController::class, 'users'])->name('users');
Route::patch('users/change-role', [UserController::class, 'changeRole'])->name('users.change-role');

Route::middleware('role:admin')->group(function () {
    Route::delete('users/{user}/roles/{role}', [RoleController::class, 'removeRole'])->name('users.remove-role');
});

Route::get('/auth/register', [AuthController::class, 'registerPage'])->name('register');
Route::post('/auth/register', [AuthController::class, 'register']);

Route::post('/auth/verify-account/{?identifier}', [AuthController::class, 'verifyAccount'])->name('verifyAccount');
Route::get('/auth/verify-account/{identifier}', [AuthController::class, 'verifyAccountPage'])->name('verifyAccountPage');
Route::post('send-verification-otp', [AuthController::class, 'sendOtp'])->name('send.otp');
Route::view('verification-method/{identifier}', 'auth.verificationMethod');

Route::middleware(['auth', 'auth.session'])->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/auth/logout/{session}', [AuthController::class, 'logoutDevice'])->name('logout.device');

    Route::get('/profile/{user}', [AuthController::class, 'profile'])->name('profile');
    Route::view('/update-profile', 'auth.updateProfile')->name('update-profile');
    Route::post('/update-profile', [AuthController::class, 'update_profile']);

    Route::get('/change-password', function (User $user) {
        return view('auth.changePassword');
    })->name('change-password');
    // Route::post('/change-password',[AuthController::class,'change_password']);
    Route::post('/change-password', [AuthController::class, 'change_password'])->name('change-password2');

    // Routes for Testing Users Roles 
    Route::view('hr-management', 'Pages.student')->middleware('role:HR');
    Route::view('browse', 'Pages.teacher')->middleware('role:user');
    Route::view('admin-dashboard', 'Pages.admin')->middleware('role:superadmin');
});

// // GOOGLE LOGIN
// Route::get('/auth/google/callback', [AuthController::class, 'google_callback']);
// Route::get('/auth/google/redirect', [AuthController::class, 'google_redirect'])->name('google');
// // GITHUB LOGIN
// Route::get('/auth/github/callback', [AuthController::class, 'github_callback']);
// Route::get('/auth/github/redirect', [AuthController::class, 'github_redirect'])->name('github');
// //FACEBOOK LOGIN 
// Route::get('/auth/facebook/callback', [AuthController::class, 'facebook_callback']);
// Route::get('/auth/facebook/redirect', [AuthController::class, 'facebook_redirect'])->name('facebook');

// SOCIAL DRIVER instead of the 6th route above .
Route::get('/auth/{driver}/callback', [AuthController::class, 'callback']);
Route::get('/auth/{driver}/redirect', [AuthController::class, 'redirect'])->name('social');

Route::view('/forget-password', 'auth.forgetPassword')->name('forget-password');
Route::post('/forget-password', [AuthController::class, 'forget_password'])->name('forget.password');

Route::view('/reset-password', 'auth.resetPassword')->name('reset-password');
Route::post('/reset-password', [AuthController::class, 'reset_password'])->name('reset.password');
