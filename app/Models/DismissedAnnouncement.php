<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DismissedAnnouncement extends Model
{
    use HasFactory;

    public $table = 'dismissed_announcements';
    protected $fillable = ['announcement_id','user_id'];

}
