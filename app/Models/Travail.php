<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Travail extends Model
{
    use HasFactory;

    protected $table = "travaux";

    protected $fillable = [
        'name',
    ];

    public static function getAll(): \Illuminate\Support\Collection
    {
        return DB::table('travaux')->orderBy('id', 'asc')->get();
    }

    public static function deleteTravail(int $id): int
    {
        return DB::table('travaux')->delete($id);
    }

    public static function getTravail(int $id)
    {
        return DB::table('travaux')->find($id);
    }

}
