<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatkulModel extends Model
{
    protected $table = 'tbl_matkul';
    protected $primaryKey = 'kode_matkul';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
    'kode_matkul',
    'nama_matkul',
    'jumlah_sks',
    'jml_cpmk',
    ];


}
