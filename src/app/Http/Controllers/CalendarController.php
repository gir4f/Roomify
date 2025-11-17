<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking; // <-- Pastikan import Booking
use Illuminate\Http\Request;
use Inertia\Inertia; // <-- Pastikan import Inertia

class CalendarController extends Controller
{
    /**
     * Menampilkan halaman kalender dengan data booking.
     */
    public function index()
    {
        // 1. Ambil semua booking yang 'pending' atau 'approved'
        // Kita tidak perlu menampilkan yang 'rejected'
        $bookings = Booking::whereIn('status', ['approved', 'pending'])
            ->with('room', 'user') // Ambil relasi agar kita dapat nama
            ->get();

        // 2. Ubah data booking menjadi format 'Event' FullCalendar
        $events = $bookings->map(function ($booking) {
            
            // Tentukan warna berdasarkan status
            $color = $booking->status === 'approved' ? '#10B981' : '#F59E0B'; // Hijau (Approved) / Kuning (Pending)

            // Buat judul event
            $title = $booking->room->name . ' - ' . $booking->title;
            
            return [
                'id' => $booking->id,
                'title' => $title,
                'start' => $booking->start_time->toIso8601String(), // Format standar
                'end' => $booking->end_time->toIso8601String(),   // Format standar
                'color' => $color,
                'extendedProps' => [
                    'status' => $booking->status,
                    'user' => $booking->user->name,
                ]
            ];
        });

        // 3. Render halaman Inertia dan kirim 'events' sebagai prop
        return Inertia::render('Calendar/Index', [
            'events' => $events,
        ]);
    }
}