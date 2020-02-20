<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $fillable = [
        'user_id', 'tanggal_mulai', 'tanggal_selesai', 'alasan', 'keterangan', 'izin_by'
    ];

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function izinBy() {
        return $this->hasOne('App\User', 'id', 'izin_by');
    }
}
