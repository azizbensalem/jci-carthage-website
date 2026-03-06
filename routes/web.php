<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\GoogleAccountController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Language Switch Route
Route::get('/lang/{lang}', [LanguageController::class, 'switchLang'])->name('lang.switch');

// Public Website Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/activities', [HomeController::class, 'activities'])->name('activities');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Blog Routes
Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{category}', [App\Http\Controllers\BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request')
    ->middleware('guest');
Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email')
    ->middleware('guest');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset')
    ->middleware('guest');
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])
    ->name('password.update')
    ->middleware('guest');

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('carousels', CarouselController::class);
    Route::resource('events', EventController::class);
    Route::resource('partners', PartnerController::class);
    Route::resource('blog', App\Http\Controllers\Admin\BlogPostController::class);
    
    // Facebook Import
    Route::get('facebook-import', [App\Http\Controllers\Admin\FacebookImportController::class, 'index'])->name('facebook.import');
    Route::post('facebook-import', [App\Http\Controllers\Admin\FacebookImportController::class, 'import'])->name('facebook.import.execute');
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    
    // Google Account Management (kept for OAuth callbacks)
    Route::get('google-account', [GoogleAccountController::class, 'index'])->name('google-account.index');
    Route::get('google-account/connect', [GoogleAccountController::class, 'connect'])->name('google-account.connect');
    Route::get('google-account/callback', [GoogleAccountController::class, 'callback'])->name('google-account.callback');
    Route::post('google-account/disconnect', [GoogleAccountController::class, 'disconnect'])->name('google-account.disconnect');
});
