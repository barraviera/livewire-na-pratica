<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Por conveção o laravel vai buscar a tabela user_plans
//mas se quisermos sobrescrever este nome podemos usar a propriedade table como abaixo
class UserPlan extends Model
{
    protected $table = 'user_plan';

    use HasFactory;

    protected $fillable = ['plan_id', 'reference_transaction', 'status', 'date_subscription'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Se quiser pegar o plano a partir do UserPlan tambem vai dar
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

}
