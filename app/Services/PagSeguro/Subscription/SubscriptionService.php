<?php


namespace App\Services\PagSeguro\Subscription;

use App\Services\PagSeguro\Credentials;
use Illuminate\Support\Facades\Http;
use Symfony\Contracts\Service\Attribute\SubscribedService;

class SubscriptionService
{
    private $data;

    //vamos receber os dados do payload que vem do form em credit-card.blade.php Livewire.emit('paymentData', payload);
    //na qual passamos para CreditCard.php e depois vem pra cá
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function makeSubscription()
    {
        //TO_DO usar o Credentials.php
        $url = Credentials::getCredentials('/pre-approvals');

        //montamos o cabeçalho indicando que será enviado como json
        //e o accept indica que o retorno nós queremos receber em json
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1'
        ])
        ->post($url, [
            'plan' => $this->data['plan_reference'], //codigo do plano
            'sender' => [
                'name' => 'Teste Usuário Sender',
                'email' => 'teste@sandbox.pagseguro.com.br',
                'hash' => $this->data['senderHash'], //o hash
                'phone' => [
                    'areaCode' => '98',
                    'number'   => '984283432'
                ],
                'address' => [
                    'street' => 'Rua Teste',
                    'number' => '29',
                    'complement' => '',
                    'district' => 'São Bernado',
                    'city' => 'São Luis',
                    'state' => 'MA',
                    'country' => 'BRA',
                    'postalCode' => '65056000'
                ],
                'documents' => [
                    [
                        'type' => 'CPF',
                        'value' => '68487516343' //cpf para teste gerardorcpf.com
                    ]
                ]
            ],
            'paymentMethod' => [
                'type' => 'CREDITCARD',
                'creditCard' => [
                    'token' => $this->data['token'], //e o token
                    'holder' => [
                        'name' => 'Customer Credit Name',
                        'birthDate' => '30/10/1990',
                        'documents' => [
                            [
                                'type' => 'CPF',
                                'value' => '68487516343'
                            ]
                        ],
                        'billingAddress' => [
                            'street' => 'Rua Teste',
                            'number' => '29',
                            'complement' => '',
                            'district' => 'São Bernado',
                            'city' => 'São Luis',
                            'state' => 'MA',
                            'country' => 'BRA',
                            'postalCode' => '65056000'
                        ],
                        'phone' => [
                            'areaCode' => '98',
                            'number'   => '984283432'
                        ]
                    ]

                ]
            ]
        ]);

        $response = (new SubscriptionReaderService())->getSubscriptionByCode($response->json()['code']);
        //teremos um array com as informações do plano
        return $response;
    }
}
