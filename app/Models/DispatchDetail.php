<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function Settlement(){
        return $this->belongsTo(Settlement::class);
    }
    public function Dispatch(){
        return $this->belongsTo(Dispatch::class);
    }
}
