<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IzinPerjam extends Model
{
    protected $fillable = [
        'user_id', 'tanggal', 'jam_mulai', 'jam_selesai', 'alasan', 'keterangan', 'izin_by'
    ];
}
