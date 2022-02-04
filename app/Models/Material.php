<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Material extends Model
{
    use HasFactory;

    protected $table = "material";

    protected $fillable = [
        'name',
    ];


    public static function getAll()
    {
        return DB::table('material')->orderBy('id', 'asc')->get();
    }

    public static function deleteMaterial(int $id)
    {
        return DB::table('material')->delete($id);
    }

    public static function getMateriaux($num)
    {
        return DB::table('material')->find($num);
    }


}
