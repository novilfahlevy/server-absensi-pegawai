<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IzinPerhari extends Model
{
    protected $fillable = [
        'user_id', 'tanggal_mulai', 'tanggal_selesai', 'alasan', 'keterangan', 'izin_by'
    ];
}
