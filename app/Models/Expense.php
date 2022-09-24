<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    //Permitir estes campos
    protected $fillable = ['user_id', 'description', 'type', 'amount'];


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

    //Ligando com o model User
    //E no model User temos o hasMany (um pra muitos)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
