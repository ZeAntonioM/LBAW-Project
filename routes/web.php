<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\ForumController;
use App\Http\Controllers\AppealController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Home


Route::redirect("/", 'home')->name('init_page');

// Decide which Home page to use
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'show')->name('home');
});

// Blocked User
Route::prefix('/blocked')->controller(AppealController::class)->group(function () {
    Route::get('', 'showBlocked')->name('blocked');
    Route::post('/create', 'store')->name('create_appeal');
});


// Recover password route
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('pass.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('pass.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('pass.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('pass.update');
});

Route::get('/accept-invite/{token}', [ForgotPasswordController::class, 'accept_invite'])->name('accept.invite');

//Static Pages
Route::get('/myProjects', [ProjectController::class, 'index'])->name('projects');

// Static Pages
Route::get('{page}', [StaticController::class, 'show'])->whereIn('page', StaticController::STATIC_PAGES)->name('static');

// API

Route::prefix('/api')->group(function () {
    Route::controller(TaskController::class)->group(function () {
        Route::get('/{project}/tasks', 'searchTasks')->name('search_tasks');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'searchUsers')->name('search_users');

        Route::get('/notifications', 'getUserNotification')->name('notifications');
        Route::get('/tasks','getUserTasks')->name('userTasks');

        Route::get('/check-user/{email}', 'checkUserExists')->name('check_user_exists');

    });
    Route::controller(ProjectController::class)->group(function () {
        Route::get('/projects', 'search')->name('search_projects');
    });
    Route::controller(LoginController::class)->group(function () {
        Route::get('/auth', 'auth')->name('auth');
    });
    Route::controller(TagController::class)->group(function () {
        Route::get('/tags', 'search')->name('search_tag');
    });

    Route::controller(NotificationController::class)->group(function (){
        Route::put('projectNotifications/seen','seenProjectNotification')->name('see_project_notification');
        Route::put('taskNotifications/seen','seenTaskNotification')->name('see_task_notification');
        Route::put('inviteNotifications/seen','seenInviteNotification')->name('see_invite_notification');
        Route::put('forumNotifications/seen','seenForumNotification')->name('see_forum_notification');
        Route::put('commentNotifications/seen','seenCommentNotification')->name('see_comment_notification');
    });

});

    Route::prefix('/admin')->controller(AdminController::class)->group(function () {
        Route::redirect('/', '/admin/users')->name('admin');
        Route::prefix('/appeals')->controller(AppealController::class)->group( function() {
            Route::get('', 'show')->name('admin_appeals');
            Route::delete('/{appeal}/deny', 'deny')->name('deny_appeal');
            Route::put('/{appeal}/accept', 'accept')->name('accept_appeal');
        });

        Route::get('/users', 'show')->name('admin_users');
        Route::get('/users/create', 'create');
        Route::post('/users/create', 'store')->name('admin_user_create');
        Route::get('/projects','showProjects')->name('admin_show_projects');
    });

// Authentication
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
    });

// Sign-up
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'showRegistrationForm')->name('register');
        Route::post('/register', 'register')->name('create_account');
    });


Route::prefix('/user-profile')->controller(ProfileController::class)->group(function () {
    Route::get('/', 'show')->name('user-profile');
    Route::get('/{user}', 'showProfile')->name('profile');
    Route::put('/{user}/edit', 'updateProfile')->name('update_profile');
    Route::get('/{user}/edit', 'showEditProfile')->name('edit_profile');
    Route::put('/{user}/update_image','updateProfileImage')->name('update_profile_image'); 
    Route::delete('/{user}/delete_image','deleteProfileImage')->name('delete_profile_image');
    Route::get('/{user}/delete','showDelete')->name('show_delete_profile');
    Route::delete('/{user}/delete', 'destroy')->name('delete_profile');
});

// Files 
Route::controller(FileController::class)->group(function () {
    Route::post('/file/upload','upload')->name('upload_file');
    Route::get('/file/delete/{file}','delete')->name('delete_file');
    Route::get('/file/download_file/{file}','download')->name('download_file');
    Route::get('/file/downloadAll','downloadAll')->name('download_all_files');
    Route::delete('/file/deleteAll','deleteAll')->name('delete_all_files');
});
// Users
    Route::prefix('/user/{user}')->whereNumber('user')->controller(UserController::class)->group(function () {
        Route::delete('/delete', 'destroy')->name('delete_user');
        Route::put('/block', 'block')->name('block_user');
        Route::put('/unblock', 'unblock')->name('unblock_user');
    });

// Projects
    Route::prefix('/project')->group(function () {
        //Create projects
        Route::controller(ProjectController::class)->group(function () {
            Route::get('/new', 'create')->name('show_new');
            Route::post('/new', 'store')->name('action_new');
        });

        Route::prefix('/{project}')->where(['project' => '[0-9]+'])->group(function () {
            Route::controller(ProjectController::class)->group(function () {
                Route::get('', 'show')->name('project');
                Route::get('/tasks/next','getNextItems')->name('get_next_page');
                Route::get('/team', 'show_team')->name('team');
                Route::post('/team/add', 'add_user')->name('addUser');
                Route::delete('team/leave', 'remove_user')->name('leave_project');
                Route::put('/team/assign-coordinator', 'assign_coordinator')->name('assign_coordinator');
                Route::delete('/team/remove', 'remove_user')->name('remove_member');
                Route::delete('', 'destroy')->name('delete_project');
                Route::put('/favorite','favorite')->name('favorite_project');
                Route::get('/edit', 'edit')->name('show_edit_project');
                Route::put('/edit', 'update')->name('action_edit_project');
                Route::post('/team/invite', 'send_email_invite')->name('send_email_invite');
                Route::put('','archive')->name('archive_project');
                Route::get('/files','show_files')->name('show_project_files');
                Route::get('/tags', 'show_tags')->name('project_tags');
            });
            Route::prefix('/tag')->controller(TagController::class)->group(function () {
                Route::post('/add','store')->name('add_tag');
            });
            Route::prefix('/task')->controller(TaskController::class)->group(function () {
                Route::get('/search', 'index')->name('index_tasks');
                Route::get('/new', 'create')->name('createTask');
                Route::post('/new', 'store')->name('newTask');

                Route::prefix('/{task}')->whereNumber('task')->group(function () {
                    Route::get('', 'show')->name('task');
                    Route::put('/edit/status', 'editStatus')->name('edit_status');
                    Route::get('/edit', 'edit')->name('edit_task');
                    Route::put('/edit','update')->name('update_task');
                    Route::get('/next','getNextItems')->name('get_next_comments');
                    Route::post('/store_comment','storeComment')->name('store_comment');
                    Route::delete('/delete/{comment}','deleteComment')->name('delete_comment');
                    Route::put('/edit/{commmet}','editComment')->name('edit_comment');
                });
            });
            Route::get('/tasks', [ProjectController::class, 'showTasks'])->name('show_tasks');

            Route::prefix('/forum')->group(function () {
                Route::controller(PostController::class)->group(function () {
                    Route::get('', 'index')->name('forum'); 
                     Route::get('/next','getNextItems')->name('get_next_posts');
                    Route::post('/new', 'store')->name('create_post');
                    Route::prefix('/{post}')->whereNumber('post')->group(function() {
                        Route::put('/edit', 'update')->name('action_edit_post');
                        Route::delete('', 'destroy')->name('delete_post');
                        
                    });
                });
            });
        });
    });


Route::get('/post/send', [PostController::class, 'send']);

Route::prefix('/tag/{tag}')->where(['tag' => '[0-9]+'])->controller(TagController::class)->group(function () {
    Route::put('/edit','update')->name('edit_tag');
    Route::delete('/delete','destroy')->name('delete_tag');
});


