<?php

namespace App\Http\Livewire\Expense;

use Livewire\Component;
use App\Models\Expense;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ExpenseEdit extends Component
{

    use WithFileUploads;

    //Recebemos o model e abaixo ajudará a manipular o updateExpense
    public Expense $expense;

    public $description;
    public $amount;
    public $type;
    public $photo;

    //regras de validacao
    protected $rules = [
        'amount' => 'required',
        'type' => 'required',
        'description' => 'required',
        'photo' => 'image|nullable',
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

        //se existir foto a ser upada...
        if($this->photo){

            //se existir uma foto antiga na pasta de fotos...
            if(Storage::disk('public')->exists($this->expense->photo))
                //iremos remover a antiga
                Storage::disk('public')->delete($this->expense->photo);

            //e inserir a foto nova
            $this->photo = $this->photo->store('expenses-photos', 'public');
        }

        //Atualizamos
        $this->expense->update([
            'description' => $this->description,
            'amount' => $this->amount,
            'type' => $this->type,
            'photo' => $this->photo ?? $this->expense->photo, //se nao tiver foto no formulario a ser atualizada iremos retornar a foto que ja existe
        ]);

        //Mostramos mensagem de sucesso
        session()->flash('message', 'Registro atualizado com sucesso!');
    }

    public function render()
    {
        return view('livewire.expense.expense-edit');
    }
}
