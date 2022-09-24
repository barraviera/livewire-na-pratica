<?php

namespace App\Http\Livewire\Expense;

use Livewire\Component;
use App\Models\Expense;


class ExpenseList extends Component
{
    public function render()
    {
        //Vamos pegar as despesas do usuario logado e paginar pra exibir
        //se tiver despesas pra este usuario retornaremos, senao retornaremos um array vazio
        $expenses = auth()->user()->expenses()->count() ? auth()->user()->expenses()->orderBy('created_at', 'DESC')->paginate(3) : [];

        return view('livewire.expense.expense-list', compact('expenses'));
    }

    public function remove($expense)
    {
        //Localizamos o registro na tabela
        $exp = auth()->user()->expenses()->find($expense);
        //Efetuamos o delete
        $exp->delete();
        //Exibimos a mensagem de sucesso
        session()->flash('message', 'Registro removido com sucesso!');
    }

}
