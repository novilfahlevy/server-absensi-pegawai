<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobdesc;

class JobdescController extends Controller
{
    public function index() {
        return response()->json(['status' => 200, 'data' => Jobdesc::all()]);
    }
}
