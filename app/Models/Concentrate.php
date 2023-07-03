<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concentrate extends Model
{
    use HasFactory;

    protected $fillable = ['concentrate','chemical_symbol'];

    public function Orders(){
        return $this->hasMany(Order::class);
    }
}
