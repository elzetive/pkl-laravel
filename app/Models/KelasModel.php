<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_kelas_matkul';
    protected $primaryKey = 'id_kelas';
    public $timestamps = false;

    protected $fillable = [
        'kode_akademik',
        'kode_matkul',
        'kode_jurusan',
        'nik',
        'nama_kelas',
        'bobot_kelas'
    ];

    public function akademik()
    {
        return $this->belongsTo(AkademikModel::class, 'kode_akademik', 'kode_akademik');
    }

    public function matkul()
    {
        return $this->belongsTo(MatkulModel::class, 'kode_matkul', 'kode_matkul');
    }

    public function jurusan()
    {
        return $this->belongsTo(JurusanModel::class, 'kode_jurusan', 'kode_jurusan');
    }

    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'nik', 'nik');
    }

    public function detail_kelas()
    {
        return $this->hasMany(DetailKelasModel::class, 'id_kelas', 'id_kelas');
    }
}
