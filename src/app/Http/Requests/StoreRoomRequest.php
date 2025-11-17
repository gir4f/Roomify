<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     * (Nanti kita bisa ubah ini agar hanya admin)
     */
    public function authorize(): bool
    {
        // Ubah dari 'false' menjadi 'true'
        // Jika 'false', validasi akan selalu gagal dengan error 403
        return true; 
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Ini adalah aturan validasi yang kita inginkan
        return [
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'facilities' => 'nullable|string',
            'gedung' => ['nullable', 'string', 'max:100'], // <-- TAMBAHKAN INI
            'lantai' => ['nullable', 'integer', 'min:0'], // <-- TAMBAHKAN INI
        
        ];
    }
}