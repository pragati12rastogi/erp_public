<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = ['item_id','prod_quantity','prod_price','total_price','per_freight_price','user_percent','final_price','final_price','price_for_user','date_of_purchase','vendor_id','description','created_by','updated_by'];

    public function created_by_user(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }

    public function updated_by_user(){
        return $this->belongsTo('App\Models\User','updated_by','id');
    }

    public function vendor(){
        return $this->belongsTo('App\Models\Vendor','vendor_id','id');
    }

    public function item(){
        return $this->belongsTo('App\Models\Item','item_id','id');
    }

    public function history(){
        return $this->hasMany(StockHistory::class,'stock_id')->orderBy('id','desc');
    }

}
