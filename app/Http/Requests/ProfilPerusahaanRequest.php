<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfilPerusahaanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $perusahaan = $this->route('perusahaan');
        
        return [
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            // Validasi nomor HP Indonesia: mulai 08/628, 10-15 digit, hanya angka
            'no_telp' => [
                'required',
                'string',
                'max:15',
                'regex:/^(\+62|62|08)[0-9]{8,13}$/'
            ],
            'jenis_usaha' => ['required', 'string', 'max:255'],
            'no_registrasi' => [
                'required', 
                'string', 
                Rule::unique('perusahaans', 'no_registrasi')->ignore($perusahaan)
            ],
            'deskripsi' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'no_telp.regex' => 'Format nomor telepon tidak valid. Gunakan format nomor HP Indonesia (08xxx atau 62xxx, 10-15 digit).',
            'jenis_usaha.required' => 'Jenis usaha wajib diisi',
            'no_registrasi.required' => 'Nomor registrasi wajib diisi',
            'no_registrasi.unique' => 'Nomor registrasi sudah terdaftar',
            'logo.image' => 'File harus berupa gambar',
            'logo.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'logo.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
} 