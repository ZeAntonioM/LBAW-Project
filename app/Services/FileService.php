<?php
namespace App\Services;

use App\Http\Controllers\FileController;

class FileService
{
public static function getProfileImage($userId)
{
    return FileController::get('user',$userId);
}
}
?>
