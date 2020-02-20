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

    private function getUserToIzin() {
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

    private function searchUserToIzin($name) {
        $users = User::where('name', 'LIKE', "%$name%")
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

    private function getCurrentIzin() {
        $izins = Izin::join('users', 'izins.user_id', '=', 'users.id')
        ->select([
            'izins.id AS izin_id',
            'users.id', 
            'users.name', 
            'users.profile', 
            'izins.tanggal_mulai',
            'izins.tanggal_selesai',
            'izins.alasan',
            'izins.keterangan',
            'izins.izin_by'
        ])
        ->where(DB::raw('UNIX_TIMESTAMP(tanggal_mulai)'), '<=', $this->now->unix())
        ->where(DB::raw('(UNIX_TIMESTAMP(tanggal_selesai) + 60 * 60 * 24)'), '>=', $this->now->unix())
        ->get()
        ->map(function($user) {
            $user['tanggal_mulai'] = Carbon::parse($user['tanggal_mulai'])->translatedFormat('l, d F Y');
            $user['tanggal_selesai'] = Carbon::parse($user['tanggal_selesai'])->translatedFormat('l, d F Y');
            $user['izin_by'] = $user->izinBy()->first()['name'];
            return $user;
        });

        return $this->responseSuccess('Data berhasil diambil', $izins);
    }

    private function getIzinRiwayat() {
        $izins = Izin::join('users', 'izins.user_id', '=', 'users.id')
        ->select([
            'users.id', 
            'users.name', 
            'users.profile', 
            'izins.tanggal_mulai',
            'izins.tanggal_selesai',
            'izins.alasan',
            'izins.keterangan',
            'izins.izin_by'
        ])
        ->where(DB::raw('(UNIX_TIMESTAMP(tanggal_selesai) + 60 * 60 * 24)'), '<', $this->now->unix())
        ->get()
        ->map(function($user) {
            $user['tanggal_mulai'] = Carbon::parse($user['tanggal_mulai'])->translatedFormat('l, d F Y');
            $user['tanggal_selesai'] = Carbon::parse($user['tanggal_selesai'])->translatedFormat('l, d F Y');
            $user['izin_by'] = $user->izinBy()->first()['name'];
            return $user;
        });

        return $this->responseSuccess('Data berhasil diambil', $izins);
    }

    private function searchIzinRiwayat($name) {
        $izins = Izin::join('users', 'users.id', '=', 'izins.user_id')
        ->select([
            'users.id', 
            'users.name', 
            'users.profile', 
            'izins.tanggal_mulai',
            'izins.tanggal_selesai',
            'izins.alasan',
            'izins.keterangan',
            'izins.izin_by'
        ])
        ->where('users.name', 'LIKE', "%$name%")
        ->where(DB::raw('(UNIX_TIMESTAMP(izins.tanggal_selesai) + 60 * 60 * 24)'), '<', $this->now->unix())
        ->get()
        ->map(function($user) {
            $user['tanggal_mulai'] = Carbon::parse($user['tanggal_mulai'])->translatedFormat('l, d F Y');
            $user['tanggal_selesai'] = Carbon::parse($user['tanggal_selesai'])->translatedFormat('l, d F Y');
            $user['izin_by'] = $user->izinBy()->first()['name'];
            return $user;
        });

        return $this->responseSuccess('Data berhasil diambil', $izins);
    }

    // PM

    private function anggotaPM() {
        return User::join('project_managers', 'project_managers.user_id', '=', 'users.id')
        ->select('users.*', 'users.id AS id')
        ->where('project_managers.pm_id', Auth::user()->id);
    }

    private function getAnggotaToIzin() {
        $users = $this->anggotaPM()
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

    private function searchAnggotaToIzin($name) {
        $users = $this->anggotaPM()
        ->where('users.name', 'LIKE', "%$name%")
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

    private function getCurrentAnggotaIzin() {
        $izins = $this->anggotaPM()
        ->join('izins', 'izins.user_id', '=', 'project_managers.user_id')
        ->select([
            'izins.id AS izin_id',
            'users.id', 
            'users.name', 
            'users.profile', 
            'izins.tanggal_mulai',
            'izins.tanggal_selesai',
            'izins.alasan',
            'izins.keterangan',
            'izins.izin_by'
        ])
        ->where(DB::raw('UNIX_TIMESTAMP(izins.tanggal_mulai)'), '<=', $this->now->unix())
        ->where(DB::raw('(UNIX_TIMESTAMP(izins.tanggal_selesai) + 60 * 60 * 24)'), '>=', $this->now->unix())
        ->get()
        ->map(function($user) {
            $user['tanggal_mulai'] = Carbon::parse($user['tanggal_mulai'])->translatedFormat('l, d F Y');
            $user['tanggal_selesai'] = Carbon::parse($user['tanggal_selesai'])->translatedFormat('l, d F Y');
            $user['izin_by'] = User::where('id', $user['izin_by'])->first()['name'];
            return $user;
        });

        return $this->responseSuccess('Data berhasil diambil', $izins);
    }

    private function getIzinRiwayatAnggota() {
        $izins = $this->anggotaPM()
        ->join('izins', 'izins.user_id', '=', 'project_managers.user_id')
        ->select([
            'users.id', 
            'users.name', 
            'users.profile', 
            'izins.tanggal_mulai',
            'izins.tanggal_selesai',
            'izins.alasan',
            'izins.keterangan',
            'izins.izin_by'
        ])
        ->where(DB::raw('(UNIX_TIMESTAMP(izins.tanggal_selesai) + 60 * 60 * 24)'), '<', $this->now->unix())
        ->get()
        ->map(function($user) {
            $user['tanggal_mulai'] = Carbon::parse($user['tanggal_mulai'])->translatedFormat('l, d F Y');
            $user['tanggal_selesai'] = Carbon::parse($user['tanggal_selesai'])->translatedFormat('l, d F Y');
            $user['izin_by'] = User::where('id', $user['izin_by'])->first()['name'];
            return $user;
        });

        foreach ( $izins as $izin ) {
            $izin->user;
            $izin->izinBy;
        }

        return $this->responseSuccess('Data berhasil diambil', $izins);
    }

    private function searchIzinRiwayatAnggota($name) {
        $izins = $this->anggotaPM()
        ->join('izins', 'izins.user_id', '=', 'project_managers.user_id')
        ->select([
            'users.id', 
            'users.name', 
            'users.profile', 
            'izins.tanggal_mulai',
            'izins.tanggal_selesai',
            'izins.alasan',
            'izins.keterangan',
            'izins.izin_by'
        ])
        ->where(DB::raw('(UNIX_TIMESTAMP(izins.tanggal_selesai) + 60 * 60 * 24)'), '<', $this->now->unix())
        ->where('users.name', 'LIKE', "%$name%")
        ->get()
        ->map(function($user) {
            $user['tanggal_mulai'] = Carbon::parse($user['tanggal_mulai'])->translatedFormat('l, d F Y');
            $user['tanggal_selesai'] = Carbon::parse($user['tanggal_selesai'])->translatedFormat('l, d F Y');
            $user['izin_by'] = User::where('id', $user['izin_by'])->first()['name'];
            return $user;
        });

        foreach ( $izins as $izin ) {
            $izin->user;
            $izin->izinBy;
        }

        return $this->responseSuccess('Data berhasil diambil', $izins);
    }

    // Middleware
    public function getUserToIzinByRole() {
        if ( $this->isAdmin() ) {
            return $this->getUserToIzin();
        }
        return $this->getAnggotaToIzin();
    }
    
    public function searchUserToIzinByRole($name) {
        if ( $this->isAdmin() ) {
            return $this->searchUserToIzin($name);
        }
        return $this->searchAnggotaToIzin($name);
    }
    
    public function getCurrentIzinByRole() {
        if ( $this->isAdmin() ) {
            return $this->getCurrentIzin();
        }
        return $this->getCurrentAnggotaIzin();
    }

    public function getIzinRiwayatByRole() {
        if ( $this->isAdmin() ) {
            return $this->getIzinRiwayat();
        }
        return $this->getIzinRiwayatAnggota();
    }

    public function searchIzinRiwayatByRole($name) {
        if ( $this->isAdmin() ) {
            return $this->searchIzinRiwayat($name);
        }
        return $this->searchIzinRiwayatAnggota($name);
    }

    public function destroy($id) {
        $izin = Izin::find($id);
        if ( $izin->count() ) {
            if ( $izin->delete() ) {
                return $this->responseSuccess('Data berhasil dihapus');
            }
            return $this->responseError('Gagal menghapus data');
        }
        return $this->responseError('Data tidak ditemukan');
    }
}
