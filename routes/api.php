<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

function getTravaux($num)
{
    return \App\Models\Travail::getTravail($num)->name;
}

function getMateriaux($num)
{
    return \App\Models\Material::getMateriaux($num)->name;
}


Route::get('/', function () {
    return "hello world";
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// material
Route::delete('/material/{id}', function ($id) {
    return \App\Models\Material::deleteMaterial($id);
});
Route::get('/material/', function () {
    return \App\Models\Material::getAll();
});
Route::post('/material/', function (Request $request) {
    $material = new \App\Models\Material(['name' => $request->get('name')]);
    $material->save();
    return $material;
});


// client
Route::delete('/client/{id}', function ($id) {
    return \App\Models\Client::deleteClient($id);
});
Route::get('/client/', function () {
    return \App\Models\Client::getAll();
});
Route::post('/client/', function (Request $request) {
    $client = new \App\Models\Client(['name' => $request->get('name'), 'professional' => $request->get('professional')]);
    $client->save();
    return $client;
});

// travail
Route::delete('/travail/{id}', function ($id) {
    return \App\Models\Travail::deleteTravail($id);
});
Route::get('/travail/', function () {
    return \App\Models\Travail::getAll();
});
Route::post('/travail/', function (Request $request) {
    $travail = new \App\Models\Travail(['name' => $request->get('name')]);
    $travail->save();
    return $travail;
});


// chantier
/**
 * @param \Illuminate\Support\Collection $chantiers
 */
function populateChantier(\Illuminate\Support\Collection $chantiers): void
{
    foreach ($chantiers as $chantier) {
        $chantier['listMateriaux'] = array_map("getMateriaux", explode("_", $chantier->materiaux));
        $chantier['listPlans'] = \App\Models\Plan::getByIdChantier($chantier->id);
        $chantier['horaires'] = \App\Models\Horaire::getByIdChantier($chantier->id);

        $travaux = \App\Models\ChantierTravaux::where('id_chantier', '=', $chantier->id)->get();
        foreach ($travaux as $travail) {
            $travail['name'] = \App\Models\Travail::getTravail($travail->id_travaux)->name;
        }
        $chantier['listTravaux'] = $travaux;
    }
}

Route::get('/chantier/', function () {
    $chantiers = \App\Models\Chantier::getAllStartNewStatus();
    populateChantier($chantiers);
    return $chantiers;
});
Route::get('/chantier-start/', function () {
    $chantiers = \App\Models\Chantier::getAllStartStatus();
    populateChantier($chantiers);
    return $chantiers;
});

Route::get('/chantier-done/', function () {
    $chantiers = \App\Models\Chantier::getAllDoneStatus();
    populateChantier($chantiers);
    return $chantiers;
});


Route::post('/nv-chantier/', function (Request $request) {
    $chantier = new \App\Models\Chantier([
        'name' => $request->get('name'),
        'adresse' => $request->get('adresse'),
        'description' => $request->get('description'),
        'client' => $request->get('client'),
        'status' => 'NEW',
        'materiaux' => $request->get('materiaux')]);
    $chantier->save();

    $travauxString = $request->get('travaux');

    $travauxArray = explode("_", $travauxString);
    foreach ($travauxArray as $element) {
        $chantierTravaux = new \App\Models\ChantierTravaux([
            'id_chantier' => $chantier->id,
            'id_travaux' => $element,
            'progress' => 0]);
        $chantierTravaux->save();
    }

    $keys = $request->files->keys();
    foreach ($keys as $key) {
        $uploadedFile = $request->file($key);
        if ($uploadedFile->isValid()) {
            $name = time() . "_" . $uploadedFile->getClientOriginalName();
            $plan = new \App\Models\Plan([
                    'id_chantier' => $chantier->id,
                    'name' => $uploadedFile->getClientOriginalName(),
                    'url' => $name]
            );
            $plan->save();
            $uploadedFile->move("files", $name);
        }
    }

    return '';
});


Route::post('/change-chantier-status/', function (Request $request) {
    $idChantier = $request->get('id');
    $status = $request->get('status');
    $chantier = \App\Models\Chantier::find($idChantier);
    $chantier->update(['status' => $status]);
    return '';
});

Route::post('/change-chantier-process/', function (Request $request) {

    $id = $request->get('id');
    $travaux = json_decode($request->get('travaux'));

    foreach ($travaux as $travail) {

        $travailModel = \App\Models\ChantierTravaux::find($travail->id);
        $travailModel->update(['progress' => $travail->progress]);
    }

    return $travaux;
});
// travaux-supp
Route::post('/change-chantier-supp/', function (Request $request) {
    $idChantier = $request->get('id');
    $supp = $request->get('supp');
    $chantier = \App\Models\Chantier::find($idChantier);
    $chantier->update(['supp' => $supp]);
    return '';
});


// materiaux-manquant
Route::get('/materiaux-manquant/', function () {
    return \App\Models\MateriauxManquant::getAll();
});

Route::get('/materiaux-manquant-qte/', function () {
    return \App\Models\MateriauxManquant::getOnlyMateriauxManquant();
});

Route::post('/materiaux-manquant/', function (Request $request) {
    $materiauxMaquant = new \App\Models\MateriauxManquant(['name' => $request->get('name'), 'qte' => 0]);
    $materiauxMaquant->save();
    return $materiauxMaquant;
});

Route::post('/materiaux-manquant-qte/', function (Request $request) {
    $id = $request->get('id');
    $qte = $request->get('qte');
    $materiauxMaquant = \App\Models\MateriauxManquant::find($id);
    $materiauxMaquant->update(['qte' => $qte]);
    return '';
});

// horaire
Route::post('/horaire/', function (Request $request) {
    $hor = new \App\Models\Horaire(
        ['debutMatin' => $request->get('debutMatin'),
            'debutSoir' => $request->get('debutSoir'),
            'finMatin' => $request->get('finMatin'),
            'finSoir' => $request->get('finSoir'),
            'date' => $request->get('date'),
            'weekday' => (int)$request->get('weekday'),
            'id_chantier' => $request->get('id')]);
    $hor->save();
    return $hor;
});


// nv photo

Route::post('/photo-chantier-upload/', function (Request $request) {
    $keys = $request->files->keys();
    error_log('Some message here.');
    $photos = array();

    foreach ($keys as $key) {
        $uploadedFile = $request->file($key);
        if ($uploadedFile->isValid()) {
            $name = time() . "_" . $uploadedFile->getClientOriginalName();
            $photo = new \App\Models\Photo([
                    'id_chantier' => $request->get('id'),
                    'name' => $uploadedFile->getClientOriginalName(),
                    'url' => $name]
            );
            $photo->save();
            $uploadedFile->move("files", $name);

            $photos[] = $photo;
        }
    }

    return $photos;
});


Route::get('/photo-chantier/', function (Request $request) {
    error_log('Some message here.');

    $id = $request->get('id');
    return \App\Models\Photo::getByIdChantier($id);
});

