<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobdesc;
use App\ProjectManager;
use App\Role;
use App\User;

class ProjectManagerController extends Controller
{
    public function index($id) {
        $users = [];

        foreach ( ProjectManager::where('pm_id', '=', $id)->get() as $user ) {
            if ( User::find($user->user_id) ) {
                $newUser = User::find($user->user_id);
                $newUser['job'] = Jobdesc::find($newUser->jobdesc_id)->name;
                $users[] = $newUser;
            }
        }

        return response()->json([
            'status' => 200, 
            'data' => $users
        ]);
    }

    public function filterMember(Request $request) {
        $users = [];

        foreach ( ProjectManager::where('pm_id', '=', $request->id)->get() as $user ) {
            if ( User::find($user->user_id) ) {
                $newUser = User::find($user->user_id);
                $newUser['job'] = Jobdesc::find($newUser->jobdesc_id)->name;
                $users[] = $newUser;
            }
        }

        $users = collect($users)->filter(function($data) use ($request) {
            if ( $request->job !== 'all' ) {
                return $data->job === $request->job;
            }
            return true;
        })->values();

        return response()->json(['status' => 200, 'data' => $users]);
    }

    public function filterPegawai(Request $request) {
        $users = [];

        foreach ( User::all() as $user ) {
            if ( !ProjectManager::where('user_id', '=', $user->id)->orWhere('pm_id', '=', $user->id)->get()->count() ) {
                $newUser = User::find($user->id);
                $newUser['job'] = Jobdesc::find($user->jobdesc_id)->name;
                $users[] = $newUser;
            }
        }

        $users = collect($users)->filter(function($data) use ($request) {
            if ( $request->job !== 'all' ) {
                return $data->job === $request->job;
            }
            return true;
        })->values();

        return response()->json(['status' => 200, 'data' => $users]);
    }

    public function showPegawai() {
        $users = [];

        foreach ( User::all() as $user ) {
            if ( !ProjectManager::where('user_id', '=', $user->id)->orWhere('pm_id', '=', $user->id)->get()->count() ) {
                $newUser = User::find($user->id);
                $newUser['job'] = Jobdesc::find($user->jobdesc_id)->name;
                $users[] = $newUser;
            }
        }

        return response()->json(['status' => 200, 'data' => $users]);
    }

    public function store(Request $request) {
        foreach ( json_decode($request->users) as $user ) {
            $checkMemberOfPM = !ProjectManager::where('user_id', '=', $user)
                ->get()
                ->count();

            if ( $checkMemberOfPM ) {
                $pm = new ProjectManager();
    
                $pm->pm_id = $request->pm;
                $pm->user_id = $user;
    
                $pm->save();
            } 
            else {
                return response()->json(['status' => 400, 'message' => 'Gagal menambah anggota']);
            }
        }
        return response()->json(['status' => 200, 'message' => 'Berhasil menambahkan anggota']);
    }

    public function destroy($pm_id, $user_id) {
        $pm = ProjectManager::where('pm_id', '=', $pm_id)->where('user_id', '=', $user_id);
        if ( $pm ) {
            if ( $pm->delete() ) {
                return response()->json(['status' => 200, 'message' => 'Pegawai berhasil dihapus']);
            }
        }
        return response()->json(['status' => 400, 'message' => 'Gagal menghapus pegawai']); 
    }
}
