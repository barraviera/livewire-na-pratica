<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    //Permitir estes campos
    protected $fillable = ['user_id', 'description', 'type', 'amount', 'photo', 'expense_date'];

    protected $dates = ['expense_date'];


    //Quando recuperarmos um valor, movemos a virgula pro lugar correto
    public function getAmountAttribute()
    {
        //retornamos da chave attributes na coluna amount e dividimos por 100
        return $this->attributes['amount'] / 100;
    }

    //Quando formos salvar o valor, iremos retirar a virgula e salvar como um inteiro
    public function setAmountAttribute($prop)
    {
        return $this->attributes['amount'] = $prop * 100;
    }

    //em $prop recebemos a data
    public function setExpenseDateAttribute($prop)
    {
        //fazemos a formatação para pode salvar corretamente no banco
        //veja que ele vai receber com o formato 30/10/2020 19:10:00
        //e depois iremos converter para salvar no banco como 2020-10-30 19:10:00
        return $this->attributes['expense_date'] = (\DateTime::createFromFormat('d/m/Y H:i:s', $prop))->format('Y-m-d H:i:s');
    }

    //Ligando com o model User
    //E no model User temos o hasMany (um pra muitos)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
