<?php

namespace App\Http\Controllers;
use App\Models\File as myFile;
use Illuminate\Http\File;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use ZipArchive;






class FileController extends Controller
{   static $diskName = 'proj_planner_files';
    public static function getHashedPart(myFile $file){
        $combinedFilename = $file->name;
        $filenameParts = explode(';', $combinedFilename);
        return isset($filenameParts[0]) ? $filenameParts[0] : null;
    }
    public static function getOriginalPart(myFile $file){
        $combinedFilename = $file->name;
        $filenameParts = explode(';', $combinedFilename);
        return isset($filenameParts[1]) ? $filenameParts[1] : null;
    }

    public static function getType(myFile $file){
        $project = $file->project;
        $hashedPart = self::getHashedPart($file);
        $filePath = '/project/'.$project->id . '/' . $hashedPart;

        if (Storage::disk('proj_planner_files')->exists($filePath)) {
            return Storage::mimeType($filePath);
        }
    
        return null; 
    }
    public static function getData(myFile $file){
        $project = $file->project;
        $hashedPart = self::getHashedPart($file);
        $filePath = '/project/'.$project->id . '/' . $hashedPart;
        if(Storage::disk('proj_planner_files')->exists($filePath)){
            $lastModifiedTime = Storage::lastModified($filePath);
            $formattedCreationTime = date('d/m/Y H:i:s', $lastModifiedTime);
            return $formattedCreationTime;
          }
          return null;
    }
    public static function getFileSize(myFile $file){
          $project = $file->project;
          $hashedPart = self::getHashedPart($file);
          $filePath = '/project/'.$project->id . '/' . $hashedPart;
          if(Storage::disk('proj_planner_files')->exists($filePath)){
            $sizeInBytes = Storage::disk('proj_planner_files')->size($filePath);
            $sizeFormatted = self::formatSize($sizeInBytes);
            return $sizeFormatted;
          }
          return null;
    }
    public static function formatSize($sizeInBytes){
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($sizeInBytes, 0);
        $unit = intval(log($bytes, 1024));

        return round($bytes / pow(1024, $unit), 2) . ' ' . $units[$unit];
    }

    private static function validRequest(Request $request):bool{
        if($request->hasFile("file") && $request->file("file")->isValid()){ 
            return true;
        }
        return false;
    }
    public static function upload(Request $request) {
        try{
        if (self::validRequest($request)) {
            
            $file = $request->file('file');

            $project_id = $request->input('id');
            $project = Project::find($project_id);
       
            if (!Gate::denies('upload', [File::class,Auth::user(),$project])) {
                abort(403); 
            }
            $filename = $file->getClientOriginalName();
            $hashedFilename = $file->hashName();
            $combinedFilename = $hashedFilename . ';' . $filename;
            myFile::create([
                'name' => $combinedFilename,
                'project_id' => $project_id,
            ]);

            Storage::putFileAs('project/'.$project_id.'/', new File($file) ,$hashedFilename);
        
            return redirect()->back()->with('success_upload', 'File uploaded successfully');
        }
        return redirect()->back()->with('error_upload', 'File not found');
    }
    catch (PostTooLargeException $e) {
        // Handle the PostTooLargeException
        return response()->back();
    }
}
    public static function delete(myFile $file){
        $project = $file->project;
        if (!Gate::denies('delete', [File::class,Auth::user(),$project])) {
            abort(403); 
        }
        if ($file) {
            $hashedPart = self::getHashedPart($file);
            Storage::disk('proj_planner_files')->delete('/project/'.$project->id.'/'.$hashedPart);
            $file->delete();
            return redirect()->back()->with('success_delete','Delete sucessfull');

        }
        return redirect()->back()->with('error_delete','File not found');
    }
    public static function deleteAll(Request $request){
        $projectId = $request->input('id');
        $project = Project::find($projectId);
        if (!Gate::denies('upload', [File::class,Auth::user(),$project])) {
            abort(403); 
        }
        if ($project) {
            $files = $project->files;
            foreach ($files as $file) {
                $hashedPart = self::getHashedPart($file);
                $filePath = '/project/'.$project->id.'/'.$hashedPart;
                Storage::disk('proj_planner_files')->delete($filePath);
                $file->delete();
            }
    
            return redirect()->back()->with('success_delete_all', 'All files deleted successfully');
        }
    
        return redirect()->back()->with('error_delete_all', 'Project not found');
    }
    public static function download(myFile $file){
        $project = $file->project;
        if (!Gate::denies('download', [File::class,Auth::user(),$project])) {
            abort(403); 
        }
        $hashedPart = self::getHashedPart($file);
        if(Storage::disk('proj_planner_files')->exists('/project/'.$project->id.'/'.$hashedPart)){
            $filePath = 'files/project/'.$project->id.'/'.$hashedPart;
            $custom_name = self::getOriginalPart($file);
            return response()->download($filePath,$custom_name);
        }
        return redirect()->back()->with('error_download','File not found');
    }
   

public static function downloadAll(Request $request)
{
    $projectId = $request->input('id');
    $project = Project::find($projectId);

    if (!Gate::denies('upload', [File::class, Auth::user(), $project])) {
        abort(403);
    }

    if ($project) {
        $files = $project->files;

        if ($files->isNotEmpty()) {
            $zipFileName = 'project_' . $project->id . '_files.zip';

            $zip = new ZipArchive;
            $zipFilePath = storage_path('app/' . $zipFileName);

            if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
                foreach ($files as $file) {
                    $hashedPart = self::getHashedPart($file);
                    $realPart = self::getOriginalPart($file);
                    $filePath = 'project/' . $project->id . '/' . $hashedPart;
                    $zip->addFile(Storage::path($filePath), $realPart);
                }

                $zip->close();

                return response()->download($zipFilePath)->deleteFileAfterSend();
            }
        }
    }

    return redirect()->back()->with('error_files', 'You do not have files to download');
}

       
}


