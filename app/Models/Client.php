<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'professional',
    ];

    public static function getAll(): \Illuminate\Support\Collection
    {
        return DB::table('clients')->orderBy('id', 'asc')->get();
    }

    public function deleteClient(int $id): int
    {
        return DB::table('clients')->delete($id);
    }
}
