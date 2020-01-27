<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;

class RoleController extends Controller
{
    public function index() {
        return response()->json(['status' => 200, 'data' => Role::all()]);
    }
}
