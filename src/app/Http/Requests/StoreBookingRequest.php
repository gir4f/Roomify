<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Closure; // <-- Pastikan ini ada

class StoreBookingRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return true; // Izinkan semua user yang login
    }

    /**
     * Dapatkan aturan validasi.
     * PASTIKAN SEMUA KEY INI ADA
     */
    public function rules(): array
    {
        return [
            // 1. Validasi 'room_id'
            'room_id' => ['required', 'integer', Rule::exists('rooms', 'id')],
            
            // 2. Validasi 'title' (INI YANG HILANG)
            'title' => ['required', 'string', 'max:255'],
            
            // 3. Validasi 'start_time' (INI YANG HILANG)
            'start_time' => [
                'required',
                'date', // 'date' di sini berarti format tanggal/waktu yang valid
                'after:now', // Waktu mulai harus setelah sekarang
                
                // === VALIDASI TABRAKAN JADWAL ===
                function (string $attribute, mixed $value, Closure $fail) {
                    $startTime = $this->start_time;
                    $endTime = $this->end_time;

                    // 'start_time' adalah gabungan (YYYY-MM-DD HH:mm)
                    // 'end_time' juga gabungan (YYYY-MM-DD HH:mm)
                    if (!$endTime) {
                        return; // Jika end_time belum diisi, lewati
                    }

                    // Cek apakah ada booking lain (yang disetujui/pending)
                    // di ruangan yang sama pada rentang waktu yang tumpang tindih
                    $isConflict = Booking::where('room_id', $this->room_id)
                        ->where('status', '!=', 'rejected') // Abaikan yang ditolak
                        ->where(function ($query) use ($startTime, $endTime) {
                            $query->where('start_time', '<', $endTime)
                                  ->where('end_time', '>', $startTime);
                        })
                        ->exists();

                    if ($isConflict) {
                        $fail('Jadwal di ruangan ini sudah terisi pada waktu tersebut.');
                    }
                }
                // === AKHIR VALIDASI TABRAKAN ===
            ],
            
            // 4. Validasi 'end_time' (INI YANG HILANG)
            'end_time' => ['required', 'date', 'after:start_time'],
        ];
    }
}