<?php

namespace App\Http\Livewire\Payment;

use App\Models\Plan;
use App\Models\User;
use App\Services\PagSeguro\Credentials;
use App\Services\PagSeguro\Subscription\SubscriptionService;
use DateTime;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class CreditCard extends Component
{
    public $sessionId;
    public Plan $plan;

    //vamos sobrescrever a propriedade listeners
    protected $listeners = [
        //paymentData é o evento que queremos escutar e em seguida indicamos o metodo que irá ser executado
        //este evento vem de credit-card.blade.php
        'paymentData' => 'proccessSubscription'
    ];

    public function mount()
    {

        $url = Credentials::getCredentials('/sessions/');

        $response = Http::post($url);
        $response = simplexml_load_string($response->body()); //quando faz o simplexml_load_string ele ja descarta as tags basicas do html

        $this->sessionId = (string) $response->id;
    }

    //esse data será um valor injetado que vem de credit-card.blade.php na linha: Livewire.emit('paymentData', payload);
    public function proccessSubscription($data)
    {

        $data['plan_reference'] = $this->plan->reference; //é a referencia do plano
        $makeSubscription = (new SubscriptionService($data))->makeSubscription(); //passamos as informações para SubscriptionService.php

        $user = auth()->user();

        //Pegar o usuario autenticado...
        $user = User::find(11);
        //Criar o plano localmente para este usuario usando o relacionamento via eloquent
        $user->plan()->create([
            'plan_id' => $this->plan->id,
            'status' => $makeSubscription['status'],
            'date_subscription' => (DateTime::createFromFormat(DATE_ATOM, $makeSubscription['date']))->format('Y-m-d H:i:s'),
            'reference_transaction' => $makeSubscription['code'],
        ]);

        session()->flash('message', 'Plano aderido com sucesso!');

        //aqui disparamos um evento para o frontend credit-card.blade.php que conseguimos pegar usando o Livewire.on('subscriptionFinished', result => { ....
        $this->emit('subscriptionFinished');
    }

    public function render()
    {
        //vamos renderizar o layout base para subscription
        return view('livewire.payment.credit-card')
            ->layout('layouts.front');
    }
}
