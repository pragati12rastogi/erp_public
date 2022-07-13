<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = ['name','email','phone','gst_no','state','district','address','firm_name','created_by','updated_by'];

    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function updated_by_user(){
        return $this->belongsTo(User::class,'updated_by');
    }

    public function stocks(){
        return $this->hasMany(Stock::class,'vendor_id');
    }

    public function state_data(){
        return $this->belongsTo(State::class,'state');
    }

    public function district_data(){
        return $this->belongsTo(District::class,'district');
    }
}
