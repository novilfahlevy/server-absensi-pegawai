<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\User;
use App\Role;
use App\Helpers\ApiResponse;
use Laravel\Passport\Passport;

class UserController extends Controller
{
    public function index()
    {

        $user = User::all();

        return response()->json(['status' => '200', 'message' => 'Sukses', 'user' => $user]);
    }

    public function show($id){
        $user = User::with('roles')->where('id',$id)->first();

        return response()->json(['status' => '200' , 'message' => 'sukses', 'user' => $user]);
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
        }
        return $this->unauthorized();
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
            $request->validate($request,[
                'current_password' => ['required', new MatchOldPassword],
                'new_password' => ['required'],
            ],
            [
                'current_password.required' => 'masukkan password anda terlebih dahulu',
                'new_password.required' => 'masukkan password baru anda terlebih daulu',
            ]);

        $id = Auth::user()->id;
        $user = User::find($id)->update([
            'password' => Hash::make($request->new_password),
        ]);

    }

    public function editProfile(Request $request)
    {
        $profile_path = storage_path('app/public/profiles');
        $request->validate($request,[
            'profile' => 'require|image|mimes:jpeg,png,svg|max:2048',
        ],
        [
            'profile.require' => 'Masukkan gambar terlebih dahulu',
            'profile.image' => 'File yang harus dimasukkan harus gambar',
            'profile.mimes' => 'Extensi gambar yang anda masukan tidak dapat digunakan',
            'profile.max' => 'Profile anda sudah melebihi batas ukuran'
        ]);
        if(!File::isDirectory($profile_path)){
            File::makeDirectory($profile_path);
        }
        $input = $request->file('profile');
        $hashNameImage = time().'_'. $input->getClientOriginalName();

        $canvas = Image::canvas(300,300);
        $resizeImage = Image::make($input)->resize(300,300, function ($constrait){
            $constrait->aspecRatio();
        });

        $canvas->insert($resizeImage, 'center');
        $canvas->save($profile_path.'/'. $hashNameImage);

        $image = new User();
        $image->profile = $hashNameImage;
        $image->save();

        return response()->json([
            'code' => 201,
            'url' => url('storage/profiles/' . $hashNameImage),
        ]);

    }

    public function unauthorized()
    {
        return response()->json(['status' => 'Unauthorized'],401);
    }

}
