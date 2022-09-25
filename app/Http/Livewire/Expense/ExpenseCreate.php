<?php

namespace App\Http\Livewire\Expense;

use Livewire\Component;
use App\Models\Expense;
use Livewire\WithFileUploads; //necessario para trabalhar com uploads


class ExpenseCreate extends Component
{

    use WithFileUploads;
    public $amount;
    public $type;
    public $description;
    public $photo;
    public $expenseDate;

    //regras de validacao
    protected $rules = [
        'amount' => 'required',
        'type' => 'required',
        'description' => 'required',
        'photo' => 'image|nullable', //nullable pra permitir que o usuario posso nao inserir uma foto se ele nao quiser
    ];

    public function createExpense()
    {
        //validar os dados antes de inserir
        $this->validate();

        //se existir foto iremos salvar na pasta expenses-photos dentro de public
        if($this->photo){
            $this->photo = $this->photo->store('expenses-photos', 'public');
        }

        auth()->user()->expenses()->create([
            'amount' => $this->amount,
            'type' => $this->type,
            'description' => $this->description,
            'user_id' => 1,
            'photo' => $this->photo, //se ela existir retornamos ela, ou seja, pegamos seu valor, senao retornaremos nulo
            'expense_date' => $this->expenseDate, //data de criação
        ]);

        session()->flash('message', 'Registro criado com sucesso!');

        //zerar todos os campos apos o registro
        $this->amount = $this->type = $this->description = null;
    }

    public function render()
    {
        return view('livewire.expense.expense-create');
    }
}
