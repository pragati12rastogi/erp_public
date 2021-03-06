<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalOrder extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_no','role_id','user_id','total_cost','total_tax','total_discount','created_by','updated_by','is_cancelled','is_converted'];
    
    public function role(){
        return $this->belongsTo(Role::class,'role_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function updated_by_user(){
        return $this->belongsTo(User::class,'updated_by','id');
    }

    public function invoices(){
        return $this->hasMany(ProposalOrderItem::class,'order_id');
    }
}
