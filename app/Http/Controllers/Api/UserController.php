<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;
use App\Jobdesc;
use Intervention\Image\Facades\Image;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        foreach ($users as $key => $user) {
            $users[$key]['job'] = Jobdesc::find($user->jobdesc_id);
        }

        return response()->json(['status' => '200', 'message' => 'Sukses', 'user' => $users]);
    }

    public function show($id)
    {
        $user = User::with('roles')->where('id', $id)->first();
        $user['jam_kerja'] = [
            'minggu1' => 130,
            'minggu2' => 140,
            'minggu3' => 150,
            'minggu4' => 160,
            'performance' => [
                'total_jam_per_minggu' => 130 + 140 + 150 + 160,
                'terlambat' => 2,
                'total_lembur' => 7
            ]
        ];
        return response()->json(['status' => '200', 'message' => 'Sukses', 'user' => $user]);
    }

    public function login()
    {
        if (Auth::attempt(['email' => request('keyword'), 'password' => request('password')]) || Auth::attempt(['username' => request('keyword'), 'password' => request('password')])) {
            $user = Auth::user();
            $roles = User::with('roles')->where('id', $user->id)->first();
            $success['id'] = $user->id;
            $success['name'] = $user->name;
            $success['role'] = $roles->roles[0]['name'];
            $success['token'] = $user->createToken('Passport Token')->accessToken;
            Passport::personalAccessTokensExpireIn(now()->addHours(12));
            return response()->json(['status' => 200, 'message' => $success]);
        } else {
            return response()->json(['status' => 401, 'message' => 'Email atau password salah!']);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Logout berhasil!']);
    }

    public function store(RegisterUserRequest $request)
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $role = Role::find(2);
        $user->assignRole($role);

        return response()->json(['status' => '200', 'message' => 'Sukses', 'user' => $user]);
    }

    public function editPassword(Request $request)
    {
        $request->validate(
            [
                'user_id' => ['required'],
                'current_password' => ['required', new MatchOldPassword],
                'new_password' => ['required'],
            ],
            [
                'current_password.required' => 'Masukkan password anda terlebih dahulu!',
                'new_password.required' => 'Masukkan password baru anda terlebih daulu!',
            ]
        );

        $id = $request->user_id;
        $user = User::find($id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        if ($user) {
            return response()->json(['code' => 200, 'message' => 'Berhasil mengganti password!', 'data' => $user]);
        }

        return response()->json(['code' => 400, 'message' => 'Password sekarang anda salah!']);
    }

    public function editProfile(Request $request)
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

        if ($request->hasFile('profile')) {
            $fileNameWithExtention = $request->file('profile')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExtention, PATHINFO_FILENAME);
            $extention = $request->file('profile')->getClientOriginalExtension();
            $filenameToStore = $fileName . '_' . time() . '.' . $extention;
            $user = User::find($request->user_id);

            if ($user->profile !== 'default.jpg') {
                Storage::delete('public/profiles/' . $user->profile);
            }

            $profileimagepath = public_path() . '/storage/profiles/';
            $profileimageUrl = '/storage/profiles/' . $filenameToStore;
            $profileimage = Image::make($request->file('profile'));
            $canvas = Image::canvas(300, 300);

            $profileimage->resize(300, 300, function ($constrait) {
                $constrait->aspectRatio();
            });

            $canvas->insert($profileimage, 'center');
            $canvas->save($profileimagepath . $filenameToStore);
            $user->profile = $filenameToStore;
            $user->save();

            return response()->json(['status' => 200, 'message' => 'Profil anda berhasil diupdate!', 'data' => url($profileimageUrl)]);
        }

        $profileimagepath = public_path() . '/storage/profiles/';
        $profileimage = Image::make($request->file('profile'));
        $canvas = Image::canvas(300, 300);

        $profileimage->resize(300, 300, function ($constrait) {
            $constrait->aspectRatio();
        });

        $canvas->insert($profileimage, 'center');
        $canvas->save($profileimagepath . $filenameToStore);
        $user->profile = $filenameToStore;
        $user->save();

        return response()->json(['status' => 200, 'message' => 'Profil anda berhasil diupdate!', 'data' => url($profileimagepath)]);
    }

    public function editKredensial(Request $request, $id)
    {
        $user = User::find($id);
        $user->fill($request->all())->save();

        return response()->json(['status' => 200, 'message' => 'Berhasil edit kredensial!', 'data' => $user]);
    }

    public function cari($name)
    {

        $user = User::where('name', 'LIKE', '%' . $name . '%')->get();

        if (!$user->isEmpty()) {
            return response()->json(['code' => 200, 'message' => 'Berhasil mencari data!', 'data' => $user]);
        }

        return response()->json(['code' => 400, 'message' => 'Kata yang anda cari tidak ditemukan!']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['status' => 200, 'message' => 'Berhasil menghapus user!']);
    }

    public function unauthorized()
    {
        return response()->json(['status' => 'Unauthorized'], 401);
    }
}
