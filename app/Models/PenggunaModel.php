<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenggunaModel extends Model
{
    protected $table = 'tbl_pengguna';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
    'username',
    'sandi',
    'peran',
    'pin',
    'nama',
    'password_changed_at'
    ];

}
