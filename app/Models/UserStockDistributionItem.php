<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStockDistributionItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','item_id','product_price','gst','discount','distributed_quantity','product_total_price'];

    public function order(){
        return $this->belongsTo(UserStockDistributionOrder::class,'order_id','id');
    }

    public function item(){
        return $this->belongsTo(Item::class,'item_id','id');
    }

    public function update_by_user(){
        return $this->belongsTo(User::class,'updated_by','id');
    }
}
