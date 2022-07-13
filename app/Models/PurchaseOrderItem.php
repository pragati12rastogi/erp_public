<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['po_id','vendor_id','item_id','product_total_price','discount','product_price','igst','sgst','cgst','distributed_quantity','created_by'];


    public function po(){
        return $this->belongsTo(PurchaseOrder::class,'po_id','id');
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class,'vendor_id','id');
    }

    public function item(){
        return $this->belongsTo(Item::class,'item_id','id');
    }

}
