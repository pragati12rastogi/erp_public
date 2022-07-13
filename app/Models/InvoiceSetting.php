<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceSetting extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'invoice_settings';
    protected $fillable = ['user_id','prefix','suffix_number_length','updated_by'];
    

    public function updated_by_user(){
        return $this->belongsTo(User::class,'updated_by','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
