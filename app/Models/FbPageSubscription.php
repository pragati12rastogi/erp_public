<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbPageSubscription extends Model
{
    use HasFactory;

    public $table = "fb_page_subscriptions";
    protected $fillable = ['subscribed_page_name','sub_access_token','subscribed_page_id','category','category_list','tasks'];

    
}
