<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public $table = "tasks";
    protected $fillable = ['name','description','start_at','end_at','created_by','assigned_to','status','priority'];

    public $casts=[
        'assigned_to'=>'array'
    ];

    public function checklists(){
        return $this->hasMany(TaskChecklist::class,'task_id','id');
    }

    public function attachments(){
        return $this->hasMany(TaskAttachment::class,'task_id','id');
    }

    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function done_checklist(){
        return $this->hasMany(TaskChecklist::class,'task_id','id')->where('status',1);
    }
}
