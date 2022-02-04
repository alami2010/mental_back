<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chantier extends Model
{
    use HasFactory;


    protected $table = "chantiers";

    public $listTravaux;
    public $listMateriaux;
    public $listPlans;

    protected $fillable = [
        'name',
        'designation',
        'adresse',
        'description',
        'client',
        'travaux',
        'materiaux',
        'status',
        'supp',
    ];

    public function listTravaux()
    {
        //return $this->belongsToMany(RelatedModel, pivot_table_name, foreign_key_of_current_model_in_pivot_table, foreign_key_of_other_model_in_pivot_table);
        return $this->belongsToMany(
            Travail::class,
            'chantier_travaux',
            'id_chantier',
            'id_travaux');
    }


    public static function getAllStartNewStatus(): \Illuminate\Support\Collection
    {
        return Chantier::query()
            ->where('status', '=', 'NEW')
            ->orWhere('status', '=', 'START')->get();
    }

    public static function getAllStartStatus(): \Illuminate\Support\Collection
    {
        return Chantier::query()
            ->where('status', '=', 'START')->get();
    }

    public static function getAllDoneStatus(): \Illuminate\Support\Collection
    {
        return Chantier::query()
            ->where('status', '=', 'DONE')->get();
    }

    public static function getById($num)
    {

        return DB::table('chantiers')->find($num);
    }

}
