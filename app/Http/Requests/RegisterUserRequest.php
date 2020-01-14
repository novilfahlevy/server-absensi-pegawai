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
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'profile' => 'image|mimes:jpeg,png,svg|max:1024',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'name.max' => 'karakter sudah melebihi batas maksimal',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email yang anda masukan salah',
            'email.unique' => 'Email ini telah ada sebelumnya',
            'password.required' => 'Password tidak boleh kosong',
            'profile.image' => 'File yang harus dimasukkan harus gambar',
            'profile.mimes' => 'Extensi gambar yang anda masukan tidak dapat digunakan',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json($validator->errors(),422)
        );
    }
}
