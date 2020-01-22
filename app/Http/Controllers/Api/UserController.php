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
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();

        // return response()->json(['status' => '200', 'message' => 'Sukses', 'user' => $user]);
        return response()->json([
            'status' => '200', 'message' => 'Sukses', 'data' =>
            [
                [
                    'id' => 1,
                    'name' => 'Rizki Maulidan',
                    'username' => 'rizki',
                    'email' => 'rizki@gmail.com',
                    'nomor_handphone' => '089898989898',
                    'alamat' => 'Jl. Juni',
                    'profile' => 'default.jpg',
                    'email_verified_at' => null,
                    'created_at' => "2020-01-22 10:10:00",
                    'updated_at' => "2020-01-22 10:10:00",
                    'jam_kerja' => [
                        'minggu1' => 10,
                        'minggu2' => 20,
                        'minggu3' => 30,
                        'minggu4' => 40,
                        'performance' => [
                            'total_jam_per_minggu' => 10 + 20 + 30 + 40,
                            'terlambat' => 20,
                            'total_lembur' => 40
                        ]
                    ]
                ],
                [
                    'id' => 2,
                    'name' => 'Ujay',
                    'username' => 'ujay',
                    'email' => 'ujay@gmail.com',
                    'nomor_handphone' => '0898765456787',
                    'alamat' => 'Jl. Maret',
                    'profile' => 'default.jpg',
                    'email_verified_at' => null,
                    'created_at' => "2020-01-22 10:10:00",
                    'updated_at' => "2020-01-22 10:10:00",
                    'jam_kerja' => [
                        'minggu1' => 50,
                        'minggu2' => 60,
                        'minggu3' => 70,
                        'minggu4' => 80,
                        'performance' => [
                            'total_jam_per_minggu' => 50 + 60 + 70 + 80,
                            'terlambat' => 10,
                            'total_lembur' => 6
                        ]
                    ]
                ],
                [
                    'id' => 3,
                    'name' => 'Bagus',
                    'username' => 'bagus',
                    'email' => 'bagus@gmail.com',
                    'nomor_handphone' => '089898976536',
                    'alamat' => 'Jl. Kin',
                    'profile' => 'default.jpg',
                    'email_verified_at' => null,
                    'created_at' => "2020-01-22 10:10:00",
                    'updated_at' => "2020-01-22 10:10:00",
                    'jam_kerja' => [
                        'minggu1' => 90,
                        'minggu2' => 100,
                        'minggu3' => 110,
                        'minggu4' => 120,
                        'performance' => [
                            'total_jam_per_minggu' => 90 + 100 + 110 + 120,
                            'terlambat' => 9,
                            'total_lembur' => 9
                        ]
                    ]
                ],
                [
                    'id' => 4,
                    'name' => 'Kinay',
                    'username' => 'kinay',
                    'email' => 'kinay@gmail.com',
                    'nomor_handphone' => '076545676523',
                    'alamat' => 'Jl. Juni',
                    'profile' => 'default.jpg',
                    'email_verified_at' => null,
                    'created_at' => "2020-01-22 10:10:00",
                    'updated_at' => "2020-01-22 10:10:00",
                    'jam_kerja' => [
                        'minggu1' => 130,
                        'minggu2' => 140,
                        'minggu3' => 150,
                        'minggu4' => 160,
                        'performance' => [
                            'total_jam_per_minggu' => 130 + 140 + 150 + 160,
                            'terlambat' => 2,
                            'total_lembur' => 7
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function show($id)
    {
        $user = User::with('roles')->where('id', $id)->first();

        return response()->json(['status' => '200', 'message' => 'sukses', 'user' => $user]);
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
        $request->validate(
            [
                'profile' => 'required|image|mimes:jpeg,png,svg|max:2048',
            ],
            [
                'profile.required' => 'Masukkan gambar terlebih dahulu',
                'profile.image' => 'File yang harus dimasukkan harus gambar',
                'profile.mimes' => 'Extensi gambar yang anda masukan tidak dapat digunakan',
                'profile.max' => 'Profile anda sudah melebihi batas ukuran'
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

            return response()->json(['status' => 200, 'message' => 'Profil anda telah di update', 'data' => url($profileimageUrl)]);
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

        return response()->json(['status' => 200, 'message' => 'Profil anda telah di update', 'data' => url($profileimagepath)]);
    }

    public function cari($name)
    {

        $user = User::where('name', 'LIKE', '%' . $name . '%')->get();

        if (!$user->isEmpty()) {
            return response()->json(['code' => 200, 'message' => 'berhasil mencari data', 'data' => $user]);
        }

        return response()->json(['code' => 400, 'message' => 'Kata yang anda cari tidak ditemukan']);
    }

    public function unauthorized()
    {
        return response()->json(['status' => 'Unauthorized'], 401);
    }
}
