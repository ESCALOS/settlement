<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getDateAttribute($value){
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function InternationalPayment() {
        return $this->hasOne(InternationalPayment::class);
    }

    public function PercentagePayable() {
        return $this->hasOne(PercentagePayable::class);
    }

    public function Law() {
        return $this->hasOne(Law::class);
    }

    public function Protection() {
        return $this->hasOne(Protection::class);
    }

    public function Deduction() {
        return $this->hasOne(Deduction::class);
    }

    public function Refinement() {
        return $this->hasOne(Refinement::class);
    }

    public function Requirement() {
        return $this->hasOne(Requirement::class);
    }

    public function Penalty() {
        return $this->hasOne(Penalty::class);
    }

    public function AllowedAmount() {
        return $this->hasOne(AllowedAmount::class);
    }

    public function PenaltyPrice() {
        return $this->hasOne(PenaltyPrice::class);
    }

    public function Order(){
        return $this->belongsTo(Order::class);
    }

    public function PayableTotal(){
        return $this->hasOne(PayableTotal::class);
    }

    public function DeductionTotal(){
        return $this->hasOne(DeductionTotal::class);
    }

    public function PenaltyTotal(){
        return $this->hasOne(PenaltyTotal::class);
    }

    public function SettlementTotal(){
        return $this->hasOne(SettlementTotal::class);
    }
}
