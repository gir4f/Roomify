<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Inertia\Inertia;
// Pastikan ini ada
use App\Http\Requests\StoreRoomRequest; 

class RoomController extends Controller
{
    /**
     * Menampilkan daftar ruangan (Halaman Index).
     */
    public function index()
    {
        // PENYEBAB MASALAH ANDA ADA DI SINI
        // Pastikan Anda mengirim 'name' dan 'capacity'
        return Inertia::render('Rooms/Index', [
            'rooms' => Room::all()->map(fn ($room) => [
                'id' => $room->id,
                'name' => $room->name,         // <-- DIPERBAIKI (dari 'nama')
                'capacity' => $room->capacity,   // <-- DIPERBAIKI (dari 'kapasitas')
                'facilities' => $room->facilities,
                'gedung' => $room->gedung, // <-- TAMBAHKAN INI
                'lantai' => $room->lantai, // <-- TAMBAHKAN INI
            ]),
        ]);
    }

    /**
     * Menampilkan form untuk membuat ruangan baru (Halaman Create).
     */
    public function create()
    {
        return Inertia::render('Rooms/Create');
    }

    /**
     * Menyimpan ruangan baru ke database.
     * (Ini sudah berhasil untuk Anda)
     */
    public function store(StoreRoomRequest $request) 
    {
        Room::create($request->validated());

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit ruangan (Halaman Edit).
     */
    public function edit(Room $room)
    {
        // 'room' di sini adalah seluruh model, jadi ini sudah benar
        return Inertia::render('Rooms/Edit', [
            'room' => $room 
        ]);
    }

    /**
     * Mengupdate data ruangan di database.
     */
    public function update(StoreRoomRequest $request, Room $room) 
    {
        $room->update($request->validated());

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    /**
     * Menghapus ruangan dari database.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        
        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}