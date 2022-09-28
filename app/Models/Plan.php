<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    //reference será o codigo do pagseguro
    protected $fillable = ['name', 'description', 'slug', 'reference', 'price'];

    //Um plano terá muitas features(hasMany)
    public function features()
    {
        return $this->hasMany(Feature::class);
    }


}
