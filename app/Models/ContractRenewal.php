<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractRenewal extends Model
{
    use HasFactory;

    public $table="contract_renewals";
    protected  $fillable = ['contract_id','start_date','end_date','value','renewal','created_by'];

    public function contract(){
        return $this->belongsTo(Contract::class,'contract_id','id');
    }

    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by','id');
    }
}
