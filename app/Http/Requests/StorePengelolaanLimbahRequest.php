<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengelolaanLimbahRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'jenis_limbah_id' => 'required|exists:jenis_limbahs,id',
            'penyimpanan_id' => 'required|exists:penyimpanans,id',
            'jumlah_dikelola' => 'required|numeric|min:0.01',
            'jenis_pengelolaan' => 'required|in:internal,vendor_eksternal',
            'metode_pengelolaan' => 'required|in:reduce,reuse,recycle,treatment,disposal,incineration,landfill,composting,stabilization',
            'vendor_id' => 'nullable|exists:vendors,id',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:diproses,dalam_pengangkutan,selesai',
            'catatan' => 'nullable|string|max:1000'
        ];
    }

    public function messages()
    {
        return [
            'tanggal_mulai.required' => 'Tanggal mulai pengelolaan wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'jenis_limbah_id.required' => 'Jenis limbah wajib dipilih.',
            'penyimpanan_id.required' => 'Penyimpanan wajib dipilih.',
            'jumlah_dikelola.required' => 'Jumlah yang dikelola wajib diisi.',
            'jumlah_dikelola.min' => 'Jumlah minimal 0.01.',
            'jenis_pengelolaan.required' => 'Jenis pengelolaan wajib dipilih.',
            'metode_pengelolaan.required' => 'Metode pengelolaan wajib dipilih.',
            'vendor_id.exists' => 'Vendor yang dipilih tidak valid.',
        ];
    }

    protected function prepareForValidation()
    {
        // Set default status if not provided
        if (empty($this->status)) {
            $this->merge(['status' => 'diproses']);
        }

        // Handle vendor_id based on jenis_pengelolaan
        if ($this->jenis_pengelolaan === 'internal') {
            $this->merge(['vendor_id' => null]);
        }
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom validation for vendor when jenis_pengelolaan is vendor_eksternal
            if ($this->jenis_pengelolaan === 'vendor_eksternal' && empty($this->vendor_id)) {
                $validator->errors()->add('vendor_id', 'Vendor wajib dipilih untuk pengelolaan eksternal.');
            }
        });
    }
}
