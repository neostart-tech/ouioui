<?php

use App\Http\Controllers\AgenceController;
use App\Http\Controllers\AgenceeleveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CalendrierController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\CourAgenceController;
use App\Http\Controllers\CourController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgrammeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfAgenceController;
use App\Http\Controllers\ProfcourController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TransactionController;
use App\Models\agencecour;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('sendmail', [MailController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// les routes du authcontroller
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}',[UserController::class,'show']);
Route::post ('/updateusers',[UserController::class,'update']);
Route::delete('/users/{id}',[UserController::class,'destroy']);
Route::put('/users/change-password/{id}',[UserController::class,'changePassword']);
Route::get('/get-auth-user/{id}', [UserController::class, 'getAuthUser']);

//les routes du cours
Route::post('/cours', [CourController::class, 'store']);
Route::get('/cours',[CourController::class,'index']);
Route::get('/cours/{id}',[CourController::class,'show']);
Route::post ('/update-cours',[CourController::class,'updateCour']);
Route::delete('/cours/{id}',[CourController::class,'destroy']);
Route::post('/cours/status/{id}',[CourController::class,'updateStatus']);

//Les routes des élèves
Route::post('/eleves', [EleveController::class, 'store']);
Route::get('/eleves',[EleveController::class,'index']);
Route::get('/eleves/{id}',[EleveController::class,'show']);
Route::get('/eleves-user/{userId}',[EleveController::class,'eleveUser']);
Route::post ('/update-eleves',[EleveController::class,'update']);
Route::delete('/eleves/{id}',[EleveController::class,'destroy']);

//les routes des programmes
Route::post('/programmes', [ProgrammeController::class, 'store']);
Route::get('/programmes',[ProgrammeController::class,'index']);
Route::get('/programmes/{id}',[ProgrammeController::class,'show']);
Route::post('/cancel-programmes/{id}',[ProgrammeController::class,'cancel']);
Route::post ('/update-programmes',[ProgrammeController::class,'updateProgramme']);
Route::post ('/prof-programmes',[ProgrammeController::class,'addProfesseur']);
Route::delete('/programmes/{id}',[ProgrammeController::class,'destroy']);
Route::get('/programmes-user/{userId}',[ProgrammeController::class,'programmeUser']);

//Les routes des classes
Route::post('/classes', [ClasseController::class, 'store']);
Route::get('/classes', [ClasseController::class,'index']);
Route::get('/classes/{id}', [ClasseController::class, 'show']);
Route::post ('/update-classes', [ClasseController::class, 'update']);
Route::delete('/classes/{id}',[ClasseController::class, 'destroy']);

//les routes des matieres
Route::post('/matieres', [MatiereController::class, 'store']);
Route::get('/matieres', [MatiereController::class,'index']);
Route::get('/matieres/{id}', [MatiereController::class, 'show']);
Route::post ('/update-matieres', [MatiereController::class, 'update']);
Route::delete('/matieres/{id}',[MatiereController::class, 'destroy']);

//les routes des agences
Route::post('/agences', [AgenceController::class, 'store']);
Route::get('/agences', [AgenceController::class, 'index']);
Route::get('/agences/{id}', [AgenceController::class, 'show']);
Route::post ('/update-agences', [AgenceController::class, 'update']);
Route::delete('/agences/{id}',[AgenceController::class, 'destroy']);

//Les routes de CourAgence
Route::post('/cours-agences', [CourAgenceController::class, 'store']);
Route::get('/cours-agences', [CourAgenceController::class, 'index']);
Route::get('/cours-agences/{id}', [CourAgenceController::class, 'show']);
Route::put('/cours-agences/{id}', [CourAgenceController::class, 'update']);
Route::delete('/cours-agences/{id}', [CourAgenceController::class, 'delete']);

//Les routes de profAgence
Route::post('/prof-agences', [ProfAgenceController::class, 'store']);
Route::get('/prof-agences', [ProfAgenceController::class, 'index']);
Route::get('/prof-agences/{id}', [ProfAgenceController::class, 'show']);
Route::post('/update-prof-agences', [ProfAgenceController::class, 'update']);
Route::delete('/prof-agences/{id}', [ProfAgenceController::class, 'destroy']);

//Les routes de profcour
Route::post('/prof-cours', [ProfcourController::class, 'store']);
Route::get('/prof-cours', [ProfcourController::class, 'index']);
Route::get('/prof-cours/{id}', [ProfcourController::class, 'show']);
Route::post('/update-prof-cours', [ProfcourController::class, 'update']);
Route::delete('/prof-cours/{id}', [ProfcourController::class, 'destroy']);

Route::post('/agence-eleve', [AgenceeleveController::class, 'saveAgenceEleve']);

//Les routes des calendriers
Route::post('/calendriers', [CalendrierController::class, 'store']);
Route::get('/calendriers',[CalendrierController::class,'index']);
Route::get('/calendriers/{id}',[CalendrierController::class,'show']);
Route::get('/status-calendriers/{id}',[CalendrierController::class,'changeStatus']);
Route::post ('/update-calendriers',[CalendrierController::class,'update']);
Route::delete('/calendriers/{id}',[CalendrierController::class,'destroy']);
Route::get('/calendriers-user/{userId}',[CalendrierController::class,'calendrierUser']);
Route::get('/calender-user/{userId}',[calendrierController::class,'userCalendrier']);


Route::get('/getSession',[StripeController::class,'getSession']);
Route::post('/stripe',[StripeController::class,'stripe']);
Route::get('/enregistrer/{id}/{email}/{status}',[StripeController::class,'enregistrer']);
Route::get('/save/{id}/{email}/{status}',[StripeController::class,'save']);

// dashboard
Route::get('/count-eleves',[EleveController::class,'elevesCount']);
Route::get('/count-professeurs',[UserController::class,'professeurCount']);
Route::get('/count-tuteurs',[UserController::class,'tuteursCount']);

//Blog
Route::post('/save-blog-publication', [BlogController::class, 'store']);
Route::get('/blog-publication', [BlogController::class, 'index']);
Route::get('/blog-publication-enable', [BlogController::class, 'blogEnable']);
Route::post('/blog-publication-update/{id}', [BlogController::class, 'update']);
Route::get('/blog-publication/{id}', [BlogController::class, 'show']);
Route::delete('/blog-publication/{id}', [BlogController::class, 'destroy']);
Route::post('/blog-status/{id}',[BlogController::class, 'changeStatut']);//Blog


// afficher les paiement
Route::get('/transactions',[TransactionController::class,'index']);

// mot de passe oublié
Route::post('/mot-de-passe-oublier', [UserController::class, 'motDePasseOublier']);

Route::post('/change-password', [UserController::class, 'resetPassword']);

// messages
Route::prefix('messages')->group(function () {
    Route::get('/', [MessageController::class, 'index']);
    Route::post('/', [MessageController::class, 'store']);
    Route::get('/{id}', [MessageController::class, 'show']);
    Route::delete('/{id}', [MessageController::class, 'destroy']);
});
