<?php

namespace App\Http\Livewire\Expense;

use Livewire\Component;
use App\Models\Expense;

class ExpenseEdit extends Component
{
    //Recebemos o model e abaixo ajudará a manipular o updateExpense
    public Expense $expense;

    public $description;
    public $amount;
    public $type;

    //regras de validacao
    protected $rules = [
        'amount' => 'required',
        'type' => 'required',
        'description' => 'required',
    ];


    //Mount é o momento em que o componente está sendo montado(um dos ciclos de vida dele)
    public function mount()
    {
        $this->description = $this->expense->description;
        $this->amount = $this->expense->amount;
        $this->type = $this->expense->type;
    }

    public function updateExpense()
    {
        //Fazemos a validação
        $this->validate();

        //Atualizamos
        $this->expense->update([
            'description' => $this->description,
            'amount' => $this->amount,
            'type' => $this->type,
        ]);

        //Mostramos mensagem de sucesso
        session()->flash('message', 'Registro atualizado com sucesso!');
    }

    public function render()
    {
        return view('livewire.expense.expense-edit');
    }
}
