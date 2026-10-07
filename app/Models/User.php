<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_pengguna';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
    'username',
    'sandi',
    'peran',
    'pin',
    'nama',
    'password_changed_at',
    ];

    public function getAuthPasswordName()
    {
        return 'sandi';
    }

}
