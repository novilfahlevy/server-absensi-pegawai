<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensis';
    protected $fillable = ['user_id', 'tanggal', 'absensi_masuk', 'absensi_keluar', 'keterangan'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
