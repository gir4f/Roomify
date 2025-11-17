<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    /**
     * Atribut yang 'boleh' diisi secara massal (mass assignable).
     * Pastikan ini sesuai dengan migrasi 'bookings' Anda.
     */
    protected $fillable = [
        'user_id',    // (Akan kita isi otomatis)
        'room_id',
        'title',      // (Nama kegiatan)
        'start_time',
        'end_time',
        'status',     // (default 'pending' dari database)
    ];

    /**
     * Tipe data cast untuk 'start_time' dan 'end_time'
     * agar menjadi objek Carbon (memudahkan format)
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Relasi: Booking ini milik siapa (User)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Booking ini untuk ruangan mana (Room)
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}