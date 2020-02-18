<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Absensi;
use App\User;
use App\Role;
use App\Izin;
use Carbon\Carbon;

class IzinController extends Controller
{
    public function getUserToIzin() {
        $users = User::all()
        ->filter(function($user) {
            return Role::find($user->id)['id'] !== 1;
        })
        ->values()
        ->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'profile' => $user->profile
            ];
        });

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil mengambil data',
            'data' => $users
        ]);
    }

    public function searchUserToIzin($name) {
        $users = User::where('name', 'LIKE', "%$name%")
        ->get()
        ->filter(function($user) {
            return Role::find($user->id)['id'] !== 1;
        })
        ->values()
        ->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'profile' => $user->profile
            ];
        });

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil mengambil data',
            'data' => $users
        ]);
    }
}
