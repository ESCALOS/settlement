<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitabilityLaw extends Model
{
    use HasFactory;

    public function Dispatch(){
        return $this->belongsTo(Dispatch::class);
    }
}
