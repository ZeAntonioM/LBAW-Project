<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\File;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Policies\FilePolicy;
use App\Policies\TaskPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Task::class => TaskPolicy::class,
        Project::class => ProjectPolicy::class,
        File::class => FilePolicy::class,
        
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
