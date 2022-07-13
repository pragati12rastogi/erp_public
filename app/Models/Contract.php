<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    public $table = 'contracts';
    protected $fillable = ['user_id','subject','value','type','start_date','end_date','description','created_by'];
    
    
    public function customer(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function created_by_user(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }
}
