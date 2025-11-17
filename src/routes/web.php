<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CalendarController; 
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


// ... (Rute Welcome/Dashboard Anda) ...

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// === GRUP UNTUK SEMUA USER YANG LOGIN (Mahasiswa, Dosen, Admin) ===
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rute untuk user biasa
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    //kalender
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
});


// === GRUP KHUSUS UNTUK ADMIN ===
// Pastikan grup ini ada dan menggunakan middleware 'admin' Anda
Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    // Rute 'rooms' Anda
    Route::resource('rooms', RoomController::class)->except(['show']);
    
    // === PASTIKAN 3 RUTE INI ADA ===
    Route::get('/admin/bookings', [BookingController::class, 'adminIndex'])->name('admin.bookings.index');
    Route::patch('/admin/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve');
    Route::patch('/admin/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('admin.bookings.reject');
    // === SELESAI ===
});

require __DIR__.'/auth.php';