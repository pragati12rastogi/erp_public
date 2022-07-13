<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestProduct extends Model
{
    use HasFactory;

    protected $fillable = ['item_id','quantity','requested_by'];
    public $table = 'request_product';

    public function item(){
        return $this->belongsTo(Item::class,'item_id','id');
    }

    public function requested_user(){
        return $this->belongsTo(User::class,'requested_by','id');
    }
}
