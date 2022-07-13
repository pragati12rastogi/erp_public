<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadSources extends Model
{
    use HasFactory;

    public $table = 'lead_sources';
    protected $fillable = ['name'];
}
