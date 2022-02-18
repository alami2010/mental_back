<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Photo extends Model
{
    use HasFactory;

    protected $table = "photos";

    protected $fillable = [
        'name',
        'id_chantier',
        'url'
    ];
    public static function getByIdChantier($id): \Illuminate\Support\Collection
    {
        return DB::table('photos')->where('id_chantier', '=', $id)->get();
    }
}
