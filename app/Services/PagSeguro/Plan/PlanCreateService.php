<?php
namespace App\Services\PagSeguro\Plan;

use App\Services\PagSeguro\Credentials;
use Illuminate\Support\Facades\Http; //http client que usaremos pra fazer a requisição na api do pagseguro

class PlanCreateService
{

    //vamos receber os dados do form criar plano
    public function makeRequest(array $data)
    {
        $url = Credentials::getCredentials('/pre-approvals/request/');

        //Criar um plano na plataforma do Pagseguro
        $response = Http::withHeaders([
            'Accept' => 'application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1', //dizendo pra api que eu sei trabalhar com este tipo
            'Content-Type' => 'application/json',
        ])->post(
            $url,

            [
                'reference' => $data['slug'],

                'preApproval' =>
                    ['name' => $data['name'],
                    'charge' => 'AUTO',
                    'period' => 'MONTHLY',
                    'amountPerPayment' => $data['price'] / 100,
                    ]
            ]
        );

        //retornamos a chave code
        return $response->json(['code']);

    }


}
