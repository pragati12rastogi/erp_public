<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Hsn extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public $table = "hsn";
    protected $fillable = ['hsn_no','created_by','updated_by'];

    public function created_by_user(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }

    public function updated_by_user(){
        return $this->belongsTo('App\Models\User','updated_by','id');
    }
}
