<?php

use App\Models\Plan;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\PagSeguro\Subscription\SubscriptionReaderService;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/notifications', function(Request $request){

    //o notificationType é o preApproval
    //e o notificationCode é o codigo da notificação
    //iremos receber um post com estes dois campos
    $data = $request->only('notificationType', 'notificationCode');

    //vamos ler a adesao do usuario pelo codigo da notificacao via api do pagseguro
    $subscription = (new SubscriptionReaderService())->getSubscriptionByNotificationCode($data['notificationCode']);

    //se existe subscription e se ele é verdadeiro
    if(isset($subscription['error']) && $subscription['error']) return response()->json(['data' => ['msg' => 'Nada encontrado']], 404);

    //pegar o plano do usuario o qual ele está participando...
    $userPlan = UserPlan::whereReferenceTransaction($subscription['code'])->first();

    //se nao encontrarmos nada, quer dizer que o usuario ainda nao tem plano
    if(!$userPlan) return response()->json(['data' => ['msg' => 'Nada encontrado']], 404);

    //atualizar este plano localmente...
    $userPlan->update(['status' => $subscription['status']]);


    //Logicas de notificar o usuario com base no status atual
    //se o status que vier do pagseguro for ACTIVE
    if($subscription['status'] == 'ACTIVE'){
        //enviar email para o usuario agradecendo...
    }

    //se o status for CANCELLED
    if($subscription['status'] == 'CANCELLED'){
        //enviar email para o usuario pedindo desculpas mas nao foi possivel renovar o plano...
    }

    //204 é um sucesso, mas sem conteudo na requisição
    return response()->json([], 204);


});
