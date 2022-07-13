<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    public $fillable = ['name','message','user_ids','created_by'];

    protected $casts = [
        'user_ids'=> 'array',
    ];
    public function created_by_user(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }

    public function dismissed(){
        return $this->hasMany(DismissedAnnouncement::class,'announcement_id');
    }
}
