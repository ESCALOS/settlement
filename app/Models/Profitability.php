<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profitability extends Model
{
    use HasFactory;

    public function Dispatch(){
        return $this->belongsTo(Dispatch::class);
    }

    public function Client(){
        return $this->belongsTo(Entity::class,'client_id');
    }
}
