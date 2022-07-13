<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAttachment extends Model
{
    use HasFactory;

    public $timestamps = False;
    public $table = 'task_attachments';
    protected $fillable = ['task_id','path','file_type','created_by'];

    public function task(){
        return $this->belongsTo(Task::class,'task_id','id');
    }

    public function created_by_user(){
        return $this->belongsTo(User::class,'created_by','id');
    }
}
