<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectNotification extends Model
{
    use HasFactory;
    public $timestamps = false;


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id');
    }
}
