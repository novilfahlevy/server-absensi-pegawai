<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobdesc;
use App\Absensi;
use App\Lembur;
use App\User;

class JobdescController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['status' => 200, 'message' => 'Sukses mendapatkan semua data!', 'data' => Jobdesc::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Jobdesc::where('name', $request->name)->first()) {
            return response()->json(['status' => 200, 'message' => "Job $request->name berhasil ditambahkan", 'data' => Jobdesc::create($request->all())->only('name')]);
        } 
        return response()->json(['status' => 400, 'message' => 'Job sudah tersedia']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(['status' => 200, 'message' => 'Berhasil mendapatkan data!', 'data' => Jobdesc::find($id)->only('name')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Jobdesc::find($id)->fill($request->all())->save();

        return response()->json(['status' => 200, 'message' => 'Berhasil update data!', 'data' => Jobdesc::find($id)->only('name')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ( Jobdesc::all()->count() > 1 ) {
            if ( Jobdesc::find($id)->delete() ) {
                $users = User::where('jobdesc_id', $id)->get()->pluck('id');
                foreach ( $users as $user ) {
                    Absensi::where('user_id', $user)->delete();
                    Lembur::where('user_id', $user)->delete();
                }
            }
        }

        return response()->json(['status' => 200, 'message' => 'Berhasil menghapus data!']);
    }

    public function replaceAllWith($replaced_job_id, $new_job_id) {
        if ( User::where('jobdesc_id', $replaced_job_id)->count() ) {
            if ( Jobdesc::where('id', $new_job_id)->count() ) {
                User::where('jobdesc_id', $replaced_job_id)->update(['jobdesc_id' => $new_job_id]);
                return response(['status' => 200]);
            }
        }
        return response(['status' => 400]);
    }
}
