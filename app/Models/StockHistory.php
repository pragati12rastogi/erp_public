<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    public $table = 'stock_history';
    protected $fillable = ['stock_id','prod_quantity','total_qty','prod_price','gst','total_price','per_freight_price','user_percent','final_price','price_for_user','date_of_purchase','vendor_id','created_by'];


    public function stock(){
        return $this->belongsTo(Stock::class,'stock_id','id');
    }
}
