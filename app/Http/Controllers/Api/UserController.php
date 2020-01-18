<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;
use Intervention\Image\Facades\Image;
use App\User;
use App\Role;
use File;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();

        return response()->json(['status' => '200', 'message' => 'Sukses', 'user' => $user]);
    }

    public function show($id)
    {
        $user = User::with('roles')->where('id', $id)->first();

        return response()->json(['status' => '200', 'message' => 'sukses', 'user' => $user]);
    }


    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['id'] = $user->id;
            $success['name'] = $user->name;
            $success['token'] = $user->createToken('Passport Token')->accessToken;
            Passport::personalAccessTokensExpireIn(now()->addHours(12));
            return response()->json(['status' => 200, 'message' => $success]);
        } else {
            return response()->json(['status' => 401, 'message' => 'Email atau password salah!']);
        }
        // return $this->unauthorized();
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Logout berhasil']);
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
                'current_password.required' => 'masukkan password anda terlebih dahulu',
                'new_password.required' => 'masukkan password baru anda terlebih daulu',
            ]
        );
        $id = $request->user_id;
        $user = User::find($id)->update([
            'password' => Hash::make($request->new_password),
        ]);
        if ($user) {
            return response()->json(['code' => 200, 'message' => 'Berhasil mengganti password', 'data' => $user]);
        }
        return response()->json(['code' => 400, 'message' => 'your current passwor was wrong']);
    }

    public function editProfile(Request $request)
    {
        $request->validate([
            'profile' => 'required|image|mimes:jpeg,png,svg|max:2048',
        ],
        [
            'profile.required' => 'Masukkan gambar terlebih dahulu',
            'profile.image' => 'File yang harus dimasukkan harus gambar',
            'profile.mimes' => 'Extensi gambar yang anda masukan tidak dapat digunakan',
            'profile.max' => 'Profile anda sudah melebihi batas ukuran'
        ]);

            if ($request->hasFile('profile')) {
                //Get filename with the extention
                $fileNameWithExtention = $request->file('profile')->getClientOriginalName();
                //get just filename
                $fileName = pathinfo($fileNameWithExtention, PATHINFO_FILENAME);
                //Get just extention
                $extention = $request->file('profile')->getClientOriginalExtension();
                //Filename to store
                $filenameToStore = $fileName.'_'.time().'.'.$extention;
                //saving Image
                $user = User::find($request->user_id);

                if($user->profile !== 'default.jpg'){
<<<<<<< HEAD
                    Storage::delete('public/profiles/'.$user->profil);
=======
                    Storage::delete('public/profiles/'.$user->profile);
>>>>>>> 77c12f66f87e4d7abac15426d7235ca87c9d1a2a
                }
                $profileimagepath = public_path().'/storage/profiles/';
                $profileimageUrl = '/storage/profiles/'.$filenameToStore;
                $profileimage = Image::make($request->file('profile'));
                $canvas = Image::canvas(300,300);
                $profileimage->resize(300,300, function ($constrait){
                    $constrait->aspectRatio();
                });
                $canvas->insert($profileimage, 'center');
                $canvas->save($profileimagepath.$filenameToStore);
                //Updating user
                $user->profile = $filenameToStore;
                $user->save();

                return response()->json(['status' => 200, 'message' => 'Profil anda telah di update' , 'data' => url($profileimageUrl)]);
            }
            $profileimagepath = public_path() . '/storage/profiles/';
            $profileimage = Image::make($request->file('profile'));
            $canvas = Image::canvas(300, 300);
            $profileimage->resize(300, 300, function ($constrait) {
                $constrait->aspectRatio();
            });
            $canvas->insert($profileimage, 'center');
            $canvas->save($profileimagepath . $filenameToStore);
            //Updating user
            $user->profile = $filenameToStore;
            $user->save();

            return response()->json(['status' => 200, 'message' => 'Profil anda telah di update', 'data' => url($profileimagepath)]);
    }

    public function cari(Request $request)
    {

        $user = User::where('name', 'LIKE', '%'. $request->input('cari') . '%')->get();
        if($user){
            return response()->json(['code' => 200, 'message' => 'berhasil mencari data' , 'data' => $user]);
        }
        return response()->json(['code' => 400, 'message' => 'Kata yang anda cari tidak ditemukan']);
    }

    public function unauthorized()
    {
        return response()->json(['status' => 'Unauthorized'], 401);
    }
}
