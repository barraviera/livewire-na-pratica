<?php
namespace App\Services\PagSeguro\Subscription;

use App\Services\PagSeguro\Credentials;
use Illuminate\Support\Facades\Http;

class SubscriptionReaderService
{

    //pre-approvals/$subscriptionCode
    //consulta pelo codigo de adesao
    public function getSubscriptionByCode($subscriptionCode)
    {
        $url = Credentials::getCredentials('/pre-approvals/' . $subscriptionCode);
        return $this->subscriptionReader($url); //passamos a url para o metodo privado subscriptionReader()
    }

    //pre-approvals/notifications/$subscriptionCode
    //consulta pelo codigo de notificacao
    public function getSubscriptionByNotificationCode($notificationCode)
    {
        $url = Credentials::getCredentials('/pre-approvals/notifications/' . $notificationCode);
        return $this->subscriptionReader($url); //passamos a url para o metodo privado subscriptionReader()
    }

    //este metodo que fará a requisicao e servirá para consultar as duas formas: pela adesao e pela notificação
    private function subscriptionReader($urlCode)
    {
        $response = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1'
            ]
        )->get($urlCode);

        return $response->json();
    }


}
