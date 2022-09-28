<?php

namespace App\Http\Livewire\Plan;

use Livewire\Component;
use App\Models\Plan;

class PlanList extends Component
{
    public function render()
    {
        //nao vamos paginar os planos pq serão pucos
        //só vamos especificar os campos que queremos mostrar
        $plans = Plan::all([
            'id',
            'name',
            'price',
            'created_at',
        ]);

        return view('livewire.plan.plan-list', compact('plans'));
    }
}
