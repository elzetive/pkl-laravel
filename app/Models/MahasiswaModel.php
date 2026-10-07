<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaModel extends Model
{
    protected $table = 'tbl_mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'nim',
        'nama',
        'kontak',
        'email',
        'kelamin',
        'img',
    ];
}
