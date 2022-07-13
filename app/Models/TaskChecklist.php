<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskChecklist extends Model
{
    use HasFactory;

    public $timestamps = False;
    public $table = 'task_checklists';
    protected $fillable = ['task_id','status','checklist','completed_by','created_by'];

    public function task(){
        return $this->belongsTo(Task::class,'task_id','id');
    }

    public function completed_by_user(){
        return $this->belongsTo(User::class,'completed_by','id');
    }

    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by','id');
    }
}
