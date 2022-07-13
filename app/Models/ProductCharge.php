<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCharge extends Model
{
    use HasFactory;

    protected $fillable = ['state_id','district_id','area_id','product_id','charges','created_by','updated_by'];

    public function state(){
        return $this->belongsTo(State::class,'state_id','id');
    }

    public function district(){
        return $this->belongsTo(District::class,'district_id','id');
    }

    public function area(){
        return $this->belongsTo(Area::class,'area_id','id');
    }

    public function product(){
        return $this->belongsTo(Item::class,'product_id','id');
    }
}
