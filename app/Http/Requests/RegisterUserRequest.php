<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use ApiResponse;

class RegisterUserRequest extends FormRequest
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
            'name' => 'required|max:100',
            'jobdesc_id' => 'required|numeric',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users,email',
            'nomor_handphone' => 'required|numeric|unique:users',
            'alamat' => 'required',
            'profile' => 'image|mimes:jpeg,png,svg|max:1024',
            'password' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.max' => 'Karakter sudah melebihi batas maksimal!',
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username telah digunakan!',
            'email.required' => 'Email tidak boleh kosong!',
            'email.email' => 'Format email yang anda masukan salah!',
            'email.unique' => 'Email telah digunakan!',
            'nomor_handphone.required' => 'Nomor handphone tidak boleh kosong!',
            'nomor_handphone.numeric' => 'Nomor handphone harus angka!',
            'nomor_handphone.unique' => 'Nomor handphone telah digunakan!',
            'password.required' => 'Password tidak boleh kosong!',
            'profile.image' => 'File harus gambar',
            'profile.mimes' => 'Ekstensi gambar yang anda masukan tidak dapat digunakan!',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ])
        );
    }
}
