<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\IzinRequest;
use App\Absensi;
use App\User;
use App\Role;
use App\Izin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IzinController extends Controller
{
    public function __construct()
    {
        $this->now = Carbon::now();
    }

    private function isAdmin() {
        return User::with('roles')
        ->where('id', Auth::user()->id)
        ->first()
        ->roles
        ->first()['id'] === 1;
    }

    private function responseError($message) {
        return response()->json(['status' => 400, 'message' => $message]);
    }

    private function responseSuccess($message, $data = null) {
        if ( $data ) {
            return response()->json([
                'status' => 200, 
                'message' => $message,
                'data' => $data
            ]);
        }
        return response()->json([
            'status' => 200, 
            'message' => $message
        ]);
    }

    // Admin

    public function getUserToIzin() {
        $users = User::select('id', 'name', 'profile')
        ->get()
        ->filter(function($user) {
            return Role::find($user->id)['id'] !== 1;
        })
        ->values()
        ->map(function($user) {
            $lastIzin = Izin::select('tanggal_mulai')
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

            $lastIzin = $lastIzin ? Carbon::parse($lastIzin['tanggal_mulai'])->translatedFormat('l, d F Y') : null;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'profile' => $user->profile,
                'izin_terakhir' => $lastIzin
            ];
        });

        return $this->responseSuccess('Data berhasil diambil', $users);
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

        return $this->responseSuccess('Data berhasil diambil', $users);
    }

    public function izinUser(IzinRequest $request) {
        if (
            !Absensi::where('user_id', $request->user_id)
            ->select('tanggal')
            ->whereBetween(DB::raw('UNIX_TIMESTAMP(tanggal)'), [
                Carbon::parse($request->tanggal_mulai)->unix(),
                Carbon::parse($request->tanggal_selesai)->unix()
            ])
            ->count()
        ) {
            if (
                !Izin::where('user_id', $request->user_id)
                ->select('tanggal_mulai', 'tanggal_selesai')
                ->where(DB::raw('UNIX_TIMESTAMP(tanggal_mulai)'), '<=', $this->now->unix())
                ->where(DB::raw('UNIX_TIMESTAMP(tanggal_selesai)'), '>=', $this->now->unix())
                ->count()
            ) {
                if ( 
                    !(Carbon::parse($request->tanggal_mulai)->unix() >
                    Carbon::parse($request->tanggal_selesai)->unix())
                ) {
                    if (
                        Carbon::parse($request->tanggal_mulai)->unix() <=
                        $this->now->unix()
                    ) {
                        if (
                            !Izin::where('tanggal_mulai', $request->tanggal_mulai)
                            ->where('tanggal_selesai', $request->tanggal_selesai)
                            ->count()
                        ) {
                            $izin = new Izin();

                            $izin->user_id = $request->user_id;
                            $izin->tanggal_mulai = $request->tanggal_mulai;
                            $izin->tanggal_selesai = $request->tanggal_selesai;
                            $izin->alasan = $request->alasan;
                            $izin->keterangan = $request->keterangan ?: null;
                            $izin->izin_by = Auth::user()->id;

                            if ( $izin->save() ) {
                                return $this->responseSuccess('Izin berhasil');
                            }

                            return $this->responseError('Izin gagal, silakan coba lagi.');
                        }

                        return $this->responseError('Izin sudah pernah dilakukan pada tanggal ini');
                    }

                    return $this->responseError('Tanggal mulai izin tidak bisa lebih dari hari ini');
                }

                return $this->responseError('Tanggal izin tidak benar');
            }

            return $this->responseError('User masih dalam izin');
        }

        return $this->responseError('User telah absen diantara tanggal izin');
    }

    public function getCurrentIzin() {
        $izins = Izin::where(DB::raw('UNIX_TIMESTAMP(tanggal_mulai)'), '<=', $this->now->unix())
        ->where(DB::raw('(UNIX_TIMESTAMP(tanggal_selesai) + 60 * 60 * 24)'), '>=', $this->now->unix())
        ->get();

        foreach ( $izins as $izin ) {
            $izin->user;
            $izin->izinBy;
        }

        return $this->responseSuccess('Data berhasil diambil', $izins);
    }

    public function getIzinRiwayat() {
        $izins = Izin::where(DB::raw('(UNIX_TIMESTAMP(tanggal_selesai) + 60 * 60 * 24)'), '<', $this->now->unix())->get();

        foreach ( $izins as $izin ) {
            $izin->user;
            $izin->izinBy;
        }

        return $this->responseSuccess('Data berhasil diambil', $izins);
    }

    public function searchIzinRiwayat($name) {
        $izins = Izin::join('users', 'users.id', '=', 'izins.user_id')
        ->where('users.name', 'LIKE', "%$name%")
        ->where(DB::raw('(UNIX_TIMESTAMP(izins.tanggal_selesai) + 60 * 60 * 24)'), '<', $this->now->unix())->get();

        foreach ( $izins as $izin ) {
            $izin->user;
            $izin->izinBy;
        }

        return $this->responseSuccess('Data berhasil diambil', $izins);
    }

    // PM

    public function getAnggotaToIzin() {
        $users = User::join('project_managers', 'project_managers.user_id', '=', 'users.id')
        ->where('project_managers.pm_id', Auth::user()->id)
        ->get()
        ->map(function($user) {
            $lastIzin = Izin::select('tanggal_mulai')
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

            $lastIzin = $lastIzin ? Carbon::parse($lastIzin['tanggal_mulai'])->translatedFormat('l, d F Y') : null;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'profile' => $user->profile,
                'izin_terakhir' => $lastIzin
            ];
        });

        return $this->responseSuccess('Data berhasil diambil', $users);
    }

    // Middleware
    public function getUserToIzinByRole() {
        if ( $this->isAdmin() ) {
            return $this->getUserToIzin();
        }
        return $this->getAnggotaToIzin();
    }
}
