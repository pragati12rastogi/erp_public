<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractTypes extends Model
{
    use HasFactory;

    public $table = 'contract_types';
    protected $fillable = ['name'];
}
