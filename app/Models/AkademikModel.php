<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkademikModel extends Model
{
    protected $table = 'tbl_akademik';
    protected $primaryKey = 'kode_akademik';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
    'kode_akademik',
    'semester',
    'tahun',
    'isactive',
    ];

}
