<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tva extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = "tvas";

    protected $fillable = [
        'name',
        'url'
    ];


    public static function getAll()
    {
        return DB::table('tvas')->orderBy('id', 'asc')->get();
    }
}
