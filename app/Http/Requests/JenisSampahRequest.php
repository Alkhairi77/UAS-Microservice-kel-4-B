<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JenisSampahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:organik,anorganik,b3,recyclable,hazardous,other',
            'harga_per_kg' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,non-aktif'
        ];
    }
}
