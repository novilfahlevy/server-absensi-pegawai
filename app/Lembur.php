<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    protected $fillable = ['user_id', 'absensi_id', 'lembur_awal', 'lembur_akhir', 'konsumsi', 'foto', 'status'];
}
