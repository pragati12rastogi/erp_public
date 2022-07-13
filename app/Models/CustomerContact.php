<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerContact extends Model
{
    use HasFactory;

    public $table = 'customer_contacts';
    protected $fillable = ['name', 'position', 'email', 'phone', 'is_primary', 'customer_id', 'created_by'];

    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

}
