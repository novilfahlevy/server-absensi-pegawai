<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbsensiKeluarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'foto_absensi_keluar' => 'required|image|mimes:jpeg,png,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'foto_absensi_keluar.required' => 'Masukkan gambar terlebih dahulu!',
            'foto_absensi_keluar.image' => 'File harus gambar!',
            'foto_absensi_keluar.mimes' => 'Ekstensi gambar yang anda masukan tidak dapat digunakan!',
            'foto_absensi_keluar.max' => 'Foto melebihi batas ukuran!'
        ];
    }
}
