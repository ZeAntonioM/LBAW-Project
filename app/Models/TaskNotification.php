<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskNotification extends Model
{
    use HasFactory;

    public $timestamps = false;


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class,'task_id');
    }
}
