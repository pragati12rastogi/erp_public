<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id','total_cost','total_tax','total_discount','created_by','updated_by','is_cancelled'];

    public function vendor(){
        return $this->belongsTo(Vendor::class,'vendor_id','id');
    }

    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function updated_by_user(){
        return $this->belongsTo(User::class,'updated_by','id');
    }

    public function po_items(){
        return $this->hasMany(PurchaseOrderItem::class,'po_id');
    }
}
