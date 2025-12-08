<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaporanHarianRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tanggal' => 'required|date|before_or_equal:today',
            'jenis_limbah_id' => 'required|exists:jenis_limbahs,id',
            'penyimpanan_id' => 'required|exists:penyimpanans,id',
            'jumlah' => 'required|numeric|min:0.01',
            'satuan' => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'tanggal.required' => 'Tanggal laporan wajib diisi.',
            'tanggal.before_or_equal' => 'Tanggal laporan tidak boleh lebih dari hari ini.',
            'jenis_limbah_id.required' => 'Jenis limbah wajib dipilih.',
            'penyimpanan_id.required' => 'Penyimpanan wajib dipilih.',
            'jumlah.required' => 'Jumlah limbah wajib diisi.',
            'jumlah.min' => 'Jumlah limbah harus lebih dari 0.',
            'satuan.required' => 'Satuan wajib diisi.',
        ];
    }
}
