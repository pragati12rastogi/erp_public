<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = ['name','category_id','gst_percent_id','hsn_id','created_by','updated_by'];

    public function category(){
        return $this->belongsTo('App\Models\Category','category_id','id');
    }
    
    public function gst_percent(){
        return $this->belongsTo('App\Models\GstPercent','gst_percent_id','id');
    }
    
    public function hsn(){
        return $this->belongsTo('App\Models\Hsn','hsn_id','id');
    }

    public function created_by_user(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }

    public function updated_by_user(){
        return $this->belongsTo('App\Models\User','updated_by','id');
    }

    public function stock(){
        return $this->belongsTo('App\Models\Stock','id','item_id');
    }

    public function images(){
        return $this->hasMany('App\Models\ItemPhoto','item_id');
    }
}
