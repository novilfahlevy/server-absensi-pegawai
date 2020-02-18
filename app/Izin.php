<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $fillable = [
        'user_id', 'tanggal_mulai', 'tanggal_selesai', 'alasan', 'keterangan', 'izin_by'
    ];
}
