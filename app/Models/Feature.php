<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    //É só vermos quais os campos tem na migration plan
    protected $fillable = ['name', 'slug', 'type'];

    //Ligando com o model Plan
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

}
