<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStock extends Model
{
    use HasFactory;

    protected $fillable = ['item_id','prod_quantity','price','user_id'];

    public function item(){
        return $this->belongsTo(Item::class,'item_id')->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
