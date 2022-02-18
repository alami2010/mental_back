<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Horaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'debutMatin',
        'debutSoir',
        'finMatin',
        'finSoir',
        'date',
        'weekday',
        'id_chantier',
    ];

    public static function getByIdChantier($id): \Illuminate\Support\Collection
    {
        return DB::table('horaires')->where('id_chantier', '=', $id)->get();
    }
}
