<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as RoleSpatie;

class Role extends RoleSpatie
{
    //
    protected $table = 'roles';

    protected $fillable =[
        'name',
        'guard_web'
    ];

    public $timestamp = true;
}
