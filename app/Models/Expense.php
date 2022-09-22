<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    //Permitir estes campos
    protected $fillable = ['description', 'type', 'amount'];


    //Ligando com o model User
    //E no model User temos o hasMany (um pra muitos)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
