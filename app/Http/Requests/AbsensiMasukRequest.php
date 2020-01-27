<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbsensiMasukRequest extends FormRequest
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
            'foto_absensi_masuk' => 'required|image|mimes:jpeg,png,svg|max:2048',
        ];
    }

    public function message()
    {
        return [
            'foto_absensi_masuk.required' => 'Masukkan gambar terlebih dahulu!',
            'foto_absensi_masuk.image' => 'File harus harus gambar!',
            'foto_absensi_masuk.mimes' => 'Extensi gambar yang anda masukan tidak dapat digunakan!',
            'foto_absensi_masuk.max' => 'Foto melebihi batas ukuran!'
        ];
    }
}
