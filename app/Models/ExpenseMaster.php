<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ExpenseMaster extends Model
{
    use HasFactory;
    public $table= "expense_masters";
    protected $fillable = ['name','created_by','updated_by'];

    public function created_by_user(){
        return $this->belongsTo('App\Models\User','created_by','id');
    }

    public function updated_by_user(){
        return $this->belongsTo('App\Models\User','updated_by','id');
    }

}
