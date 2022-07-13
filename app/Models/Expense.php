<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['name','amount','type_amount','datetime','created_by','description'];

    protected $casts = [
        'name'=>'array',
        'type_amount'=>'array'
    ];
    public function created_by_user(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }

}
