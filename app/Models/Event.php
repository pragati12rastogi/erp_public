<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    public $table = "events";
    public $fillable = ['title','description','start_date','end_date','event_color','public_event','created_by'];

    public function created_by_user(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }
}
