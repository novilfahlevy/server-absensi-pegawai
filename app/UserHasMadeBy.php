<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserHasMadeBy extends Model
{
    protected $table = 'user_has_made_by';

    protected $fillable = ['admin_id', 'user_id'];
}
