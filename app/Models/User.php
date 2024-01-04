<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;


// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_blocked',
        'projects',
        'file',
        'appeal',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_blocked' => 'boolean',
    ];


    public function projects_for_user(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_user', 'user_id', 'project_id');  
    }
    public function tasks(): BelongsToMany{
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id')->with('project'); 

    }
    protected $attributes = [
        'is_admin' => false,
        'is_blocked' => false,
    ];

    public function projects(): BelongsToMany {
        return $this->belongsToMany(Project::class);
    }

    public function assign(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }

    public function coordinates(): HasMany
    {
        return $this->hasMany(Project::class,'user_id');
    }

    public function openedTasks(): HasMany {
        return $this->hasMany(Task::class, 'opened_user_id');

    }

    public function closedTasks(): HasMany {
        return $this->hasMany(Task::class, 'closed_user_id');

    }
    public function projectNotifications(): HasMany
    {
        return $this->hasMany(ProjectNotification::class,'user_id');
    }
    public function inviteNotifications(): HasMany
    {
        return $this->hasMany(InviteNotification::class,'user_id');
    }
    public function commentNotifications(): HasMany
    {
        return $this->hasMany(CommentNotification::class,'user_id');
    }
    public function postNotifications(): HasMany
    {
        return $this->hasMany(ForumNotification::class,'user_id');
    }
    public function taskNotifications(): HasMany
    {
        return $this->hasMany(TaskNotification::class,'user_id');
    }

    public function image(){
        return ProfileController::getImage($this);
    }

    public function favoriteProjects(): BelongsToMany{
        return $this->belongsToMany(Project::class, 'favorites', 'user_id', 'project_id');
    }
    public function appeal() {
        return $this->hasOne(Appeal::class, 'user_id');

    }
}

 

