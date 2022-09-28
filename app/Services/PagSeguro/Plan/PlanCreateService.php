<?php
namespace App\Services\PagSeguro\Plan;

use Illuminate\Support\Facades\Http; //http client que usaremos pra fazer a requisição na api do pagseguro

class PlanCreateService
{
    //atributos
    private $email;
    private $token;

    //construtor
    public function __construct()
    {
        $this->email = config('pagseguro.email'); //para pegar um dado da pasta config temos que usar o helper config + nomedoarquivo.chave
        $this->token = config('pagseguro.token'); //veja que estamos acessando o arquivo pagseguro da pasta config e pegando os dados email e token
    }


    //vamos receber os dados do form criar plano
    public function makeRequest(array $data)
    {

        //Criar um plano na plataforma do Pagseguro
        $response = Http::withHeaders([
            'Accept' => 'application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1', //dizendo pra api que eu sei trabalhar com este tipo
            'Content-Type' => 'application/json',
        ])->post(
            "https://ws.sandbox.pagseguro.uol.com.br/pre-approvals/request/?email={$this->email}&token={$this->token}", //1º parametro é a url do post criar plano
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
