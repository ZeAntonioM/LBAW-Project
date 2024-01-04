<?php

namespace App\Models;

use App\Events\taskNotificationEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'status',
        'description',
        'deadline',
        'closed_user_id',
    ];

    public function created_by(): BelongsTo {
        return $this->belongsTo(User::class, 'opened_user_id')->withDefault([
            'name' => 'deleted_user',
            'email' => 'deleted_email@gmail.com',
            'image' => asset('img/default_user.jpg'),
        ]);
    }

    public function closed_by(): BelongsTo {
        return $this->belongsTo(User::class, 'closed_user_id')->withDefault([
            'name' => 'deleted_user',
            'email' => 'deleted_email@gmail.com',
            'image' => asset('img/default_user.jpg'),
        ]);
    }

    public function assigned(): BelongsToMany {
        return $this->belongsToMany(User::class);
    }

    public function tags(): BelongsToMany {
        return $this->belongsToMany(Tag::class);
    }

    public function project():BelongsTo
    {
        return $this->belongsTo(Project::class,'project_id');
    }

    public function taskNotifications(): HasMany
    {
        return $this->hasMany(taskNotification::class,'user_id');
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class,'task_id');
    }

}
