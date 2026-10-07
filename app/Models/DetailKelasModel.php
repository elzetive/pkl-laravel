<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKelasModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_detail_kelas';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;
    protected $fillable = [
        'id_kelas',
        'nim',
    ];
    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'nim');
    }
}
