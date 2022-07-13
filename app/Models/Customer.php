<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name','email','company','phone','address','country','state','city','pincode','created_by'];
    
    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function primary(){
        return $this->belongsTo(CustomerContact::class,'customer_id')->where('is_primary',1);
    }
}
