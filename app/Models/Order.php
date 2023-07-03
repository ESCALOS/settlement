<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getDateAttribute($value){
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function Client(){
        return $this->belongsTo(Entity::class,'client_id');
    }

    public function Carriage(){
        return $this->belongsTo(Entity::class,'carriage_company_id');
    }

    public function WeighingScale(){
        return $this->belongsTo(Entity::class,'weighing_scale_company_id');
    }

    public function Concentrate(){
        return $this->belongsTo(Concentrate::class);
    }
}
