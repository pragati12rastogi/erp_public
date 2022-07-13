<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function created_by_user(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }

    public function updated_by_user(){
        return $this->belongsTo('App\Models\User','updated_by','id');
    }

    public function district(){
        return $this->hasMany(District::class,'state_id');
    }
}
