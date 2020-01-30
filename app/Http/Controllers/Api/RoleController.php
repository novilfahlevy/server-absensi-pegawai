<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Role;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(['status' => 200, 'data' => Role::all()]);
    }
}
