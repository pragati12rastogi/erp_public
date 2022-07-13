<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStockDistributionOrder extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_no','user_name','address','phone','email','total_cost','total_tax','total_discount','created_by','is_cancelled','updated_by'];

    public function items(){
        return $this->hasMany(UserStockDistributionItem::class,'order_id');
    }

    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function payment(){
        return $this->hasMany(DistributionPayment::class,'local_order_id');
    }

    public function updated_by_user(){
        return $this->belongsTo(User::class,'updated_by','id');
    }
}
