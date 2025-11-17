<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest; // Untuk validasi
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingNotification; // Mailable gabungan kita

class BookingController extends Controller
{
    /**
     * ===============================================
     * METHOD UNTUK USER BIASA (Mahasiswa/Dosen)
     * ===============================================
     */

    /**
     * Menampilkan daftar booking milik user yang sedang login.
     * (GET /bookings)
     */
    public function index()
    {
        $userBookings = Booking::where('user_id', Auth::id())
            ->with('room') // Ambil relasi room (penting untuk info gedung/lantai)
            ->orderBy('start_time', 'desc')
            ->get();
            
        return Inertia::render('Bookings/Index', [
            'bookings' => $userBookings,
        ]);
    }

    /**
     * Menampilkan form untuk membuat booking baru.
     * (GET /bookings/create)
     */
    public function create()
    {
        return Inertia::render('Bookings/Create', [
            // Kirim semua data ruangan yang diperlukan untuk dropdown bertingkat
            'rooms' => Room::all(['id', 'name', 'capacity', 'gedung', 'lantai']),
        ]);
    }

    /**
     * Menyimpan booking baru ke database.
     * (POST /bookings)
     */
    public function store(StoreBookingRequest $request)
    {
        // Ambil data yang sudah divalidasi
        $validatedData = $request->validated();
        
        // Tambahkan user_id dari user yang sedang login
        $validatedData['user_id'] = Auth::id();

        // Status 'pending' akan di-set by default oleh migrasi database

        Booking::create($validatedData);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil diajukan. Menunggu persetujuan admin.');
    }


    /**
     * ===============================================
     * METHOD KHUSUS UNTUK ADMIN
     * ===============================================
     */

    /**
     * Menampilkan SEMUA booking untuk Admin.
     * (GET /admin/bookings)
     */
    public function adminIndex()
    {
        $allBookings = Booking::with('user', 'room') 
            ->orderBy('start_time', 'desc') 
            ->get();
        
        return Inertia::render('Admin/Bookings/Index', [
            'bookings' => $allBookings,
        ]);
    }

    /**
     * Menyetujui booking.
     * (PATCH /admin/bookings/{booking}/approve)
     */
    public function approve(Booking $booking)
    {
        $booking->update(['status' => 'approved']);
        
        // Kirim email notifikasi gabungan dengan status 'approved'
        Mail::to($booking->user->email)->send(new BookingNotification($booking, 'approved'));

        return redirect()->back()->with('success', 'Booking berhasil disetujui.');
    }

    /**
     * Menolak booking.
     * (PATCH /admin/bookings/{booking}/reject)
     */
    public function reject(Booking $booking)
    {
        $booking->update(['status' => 'rejected']);
        
        // Kirim email notifikasi gabungan dengan status 'rejected'
        Mail::to($booking->user->email)->send(new BookingNotification($booking, 'rejected'));

        return redirect()->back()->with('success', 'Booking berhasil ditolak.');
    }
}