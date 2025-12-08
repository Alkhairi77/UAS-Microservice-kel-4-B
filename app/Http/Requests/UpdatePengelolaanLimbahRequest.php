<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengelolaanLimbahRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tanggal_mulai' => 'required|date',
            'penyimpanan_id' => 'required|exists:penyimpanans,id',
            'jumlah_dikelola' => 'required|numeric|min:0.01',
            'jenis_pengelolaan' => 'required|in:internal,vendor_eksternal',
            'metode_pengelolaan' => 'required|in:reduce,reuse,recycle,treatment,disposal,incineration,landfill,composting,stabilization',
            'vendor_id' => 'required_if:jenis_pengelolaan,vendor_eksternal|exists:vendors,id',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'required|in:diproses,dalam_pengangkutan,selesai,dibatalkan',
            'catatan' => 'nullable|string|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'tanggal_mulai.required' => 'Tanggal mulai pengelolaan wajib diisi.',
            'penyimpanan_id.required' => 'Penyimpanan wajib dipilih.',
            'jumlah_dikelola.required' => 'Jumlah yang dikelola wajib diisi.',
            'jumlah_dikelola.min' => 'Jumlah minimal 0.01.',
            'jenis_pengelolaan.required' => 'Jenis pengelolaan wajib dipilih.',
            'metode_pengelolaan.required' => 'Metode pengelolaan wajib dipilih.',
            'vendor_id.required_if' => 'Vendor wajib dipilih untuk pengelolaan eksternal.',
            'status.required' => 'Status wajib dipilih.',
        ];
    }

    protected function prepareForValidation()
    {
        if (empty($this->vendor_id)) {
            $this->merge(['vendor_id' => null]);
        }
    }
}