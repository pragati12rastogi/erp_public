<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    public $table = 'holidays';
    protected $fillable = ['name','date','created_by'];

    public function created_by_user(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }
}
