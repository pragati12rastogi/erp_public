<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    public $table = "leads";

    protected $fillable = ['name','email','phonenumber','description','source','status','assigned_to','address','city','state','country','lead_value','company','cust_status','pincode'];

    public function assigned_user(){
        return $this->belongsTo(User::class,'assigned_to');
    }
    
    public function status(){
        return $this->belongsTo(LeadStatus::class,'status');
    }

    public function source(){
        return $this->belongsTo(LeadSources::class,'source');
    }
}
