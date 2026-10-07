<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurusanModel extends Model
{
    protected $table = 'tbl_jurusan';
    protected $primaryKey = 'kode_jurusan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'kode_jurusan',
        'nama_jurusan',
    ];

}
