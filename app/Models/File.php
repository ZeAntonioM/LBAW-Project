<?php

namespace App\Models;

use App\Http\Controllers\FileController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Symfony\Component\Finder\Iterator\FilecontentFilterIterator;

class File extends Model
{
    use HasFactory;

    public $timestamps  = false;
    protected $fillable = [
        'name',
        'project_id',
    ];
    
    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }
    
    public function getFormattedSizeAttribute(){
        return FileController::getFileSize($this);
    }
    public function getCreationData(){
        return FileController::getData($this);
    }
    public function getMimeType(){
        return FileController::getType($this);
    }
    public function getRealName(){
        return FileController::getOriginalPart($this);
    }

}