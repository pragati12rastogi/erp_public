<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;
    public $table = 'leaves';
    protected $fillable = ['user_id','from_date','to_date','reason','approved'];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

}
