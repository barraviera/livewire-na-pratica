<?php

namespace App\Http\Livewire\Plan;

use Livewire\Component;
use App\Models\Plan;
use App\Services\PagSeguro\Plan\PlanCreateService;

class PlanCreate extends Component
{

    //criamos um atributo com o nome de plan e inicializamos ele como array vazio
    //a tipagem array só está disponivel a partir do php 7.4
    public array $plan = []; //lembre-se que no form tivemos que colocar no wire plan.name plan.price etc

    //regras de validação
    protected $rules = [
        'plan.name' => 'required',
        'plan.description' => 'required',
        'plan.price' => 'required',
        'plan.slug' => 'required',

    ];

    public function createPlan()
    {

        $this->validate();

        $plan = $this->plan; //aqui temos todos os dados vindos do formulario

        $planPagSeguroReference = (new PlanCreateService())->makeRequest($plan);

        $plan['reference'] = $planPagSeguroReference; //preenchemos o campo reference separado

        Plan::create( $plan );

        $this->plan = [];

        session()->flash('message', 'Plano criado com sucesso!');
    }

    public function render()
    {
        return view('livewire.plan.plan-create');
    }
}
