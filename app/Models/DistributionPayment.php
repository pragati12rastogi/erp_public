<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionPayment extends Model
{
    use HasFactory;

    protected $fillable = ['admin_order_id','local_order_id','amount','transaction_type','transaction_id','cheque_no','bank_name','ifsc','account_name','created_by'];


    public function admin_order(){
        return $this->belongsTo(DistributionOrder::class,'admin_order_id','id');
    }

    public function local_order(){
        return $this->belongsTo(UserStockDistributionOrder::class,'local_order_id','id');
    }
}
