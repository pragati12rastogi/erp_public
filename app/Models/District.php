<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['name','state_id'];

    public function state(){
        return $this->belongsTo('App\Models\State','state_id','id');
    }

    public function created_by_user(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }

    public function updated_by_user(){
        return $this->belongsTo('App\Models\User','updated_by','id');
    }

    public function areas(){
        return $this->hasMany(Area::class,'district_id');
    }
}
