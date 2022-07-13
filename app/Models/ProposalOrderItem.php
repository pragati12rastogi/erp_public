<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalOrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','user_id','item_id','product_total_price','discount','product_price','igst','sgst','cgst','distributed_quantity','created_by'];

    public function order(){
        return $this->belongsTo(DistributionOrder::class,'order_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function item(){
        return $this->belongsTo(Item::class,'item_id','id');
    }

    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function updated_by_user(){
        return $this->belongsTo(User::class,'updated_by','id');
    }
}
