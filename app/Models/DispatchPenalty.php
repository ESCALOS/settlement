<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchPenalty extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function Dispatch(){
        return $this->belongsTo(Dispatch::class);
    }
}
