<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    protected $fillable = ['user_id', 'absensi_id', 'tanggal', 'lembur_awal', 'lembur_akhir', 'konsumsi', 'foto', 'status'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function absensi()
    {
        return $this->hasOne('App\Absensi');
    }
}
