<?php

namespace App\Http\Controllers\Api\Android;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Laravel\Passport\Passport;
use App\Jobdesc;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    private $imagePath;

    public function __construct()
    {
        $this->imagePath = public_path() . '/storage/profiles/';
    }
    public function login()
    {
        if (
            Auth::attempt(['email' => request('keyword'), 'password' => request('password')]) || Auth::attempt(['username' => request('keyword'), 'password' => request('password')])
        ) {
            $user = Auth::user();
            $roles = User::with('roles')->where('id', $user->id)->first();
            $success['id'] = $user->id;
            $success['name'] = $user->name;
            $success['role'] = $roles->roles[0]['name'];
            $success['token'] = $user->createToken('Passport Token')->accessToken;
            Passport::personalAccessTokensExpireIn(now()->addHours(12));

            return response()->json(['status' => 200, 'message' => 'Login Berhasil!', 'data' => $success]);
        }

        return response()->json(['status' => 400, 'message' => 'Username atau password salah!'], 400);
    }

    public function getProfile($id)
    {
        $users = User::where('id', $id)->get(['jobdesc_id', 'name', 'email', 'nomor_handphone', 'profile']);

        if (count($users) > 0) {
            foreach ($users as $key => $user) {
                $users[$key]['profile_image'] = url('storage/profiles/' . $user->profile);
                $users[$key]['jobdesc'] = Jobdesc::find($user->jobdesc_id)->name;
            }
            return response()->json(['status' => 200, 'message' => 'Berhasil mengambil data profile!', 'data' => $user]);
        }
        return response()->json(['status' => 400, 'message' => 'Gagal mengambil data profile!'], 400);
    }

    public function gantiPassword(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        if ($user) {
            return response()->json(['status' => 200, 'message' => 'Berhasil mengganti password!']);
        }

        return response()->json(['status' => 400, 'message' => 'Password sekarang anda salah!'], 400);
    }

    public function changeProfilePicture(Request $request)
    {
        $request->validate(
            [
                'profile' => 'required|image|mimes:jpeg,png,svg|max:2048',
            ],
            [
                'profile.required' => 'Masukkan gambar terlebih dahulu!',
                'profile.image' => 'File harus gambar!',
                'profile.mimes' => 'Ekstensi gambar tidak valid!',
                'profile.max' => 'Profile anda sudah melebihi batas ukuran!'
            ]
        );

        if (!File::isDirectory($this->imagePath)) {
            File::makeDirectory($this->imagePath);
        }

        $user = new User();

        $input = $request->file('profile');
        $hashNameImage = time() . '_' . $input->getClientOriginalName();
        $canvas = Image::canvas(500, 500);
        $resizeImage = Image::make($input)->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        });
        $canvas->insert($resizeImage, 'center');
        $canvas->save($this->imagePath . '/' . $hashNameImage);
        $user->profile = $hashNameImage;
        $user->where('email', '=', Auth::user()->email)->update(['profile' => $hashNameImage]);

        return response()->json(['status' => 200, 'message' => 'Profil anda berhasil diupdate!', 'data' => url('/storage/profiles/' . $hashNameImage)]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['status' => 200, 'message' => 'Logout berhasil!']);
    }
}
