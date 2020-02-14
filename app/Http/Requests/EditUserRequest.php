<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'jobdesc_id' => 'required|numeric',
            'role_id' => 'required|numeric',
            'username' => 'required',
            'email' => 'required|email,email',
            'nomor_handphone' => 'required|numeric',
            'alamat' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.max' => 'Karakter sudah melebihi batas maksimal!',
            'username.required' => 'Username tidak boleh kosong!',
            'jobdesc_id.required' => 'Job tidak boleh kosong!',
            'role_id.required' => 'Role tidak boleh kosong!',
            'email.required' => 'Email tidak boleh kosong!',
            'email.email' => 'Format email yang anda masukan salah!',
            'nomor_handphone.required' => 'Nomor handphone tidak boleh kosong!',
            'nomor_handphone.numeric' => 'Nomor handphone harus angka!',
            'alamat.required' => 'Alamat tidak boleh kosong!'
        ];
    }
}
