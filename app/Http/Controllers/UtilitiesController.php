<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;
use App\Custom\Constants;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use DotenvEditor;

class UtilitiesController extends Controller
{
    
    public function databaseBackupIndex(){
        
        $backups = [];
        $folder_path = Constants::backups_folder();
        
        if(file_exists($folder_path)){
            $backups = array_reverse(glob("$folder_path/*"));
        }
        
        return view('backup.index',compact('backups','folder_path'));
    }

    public function databaseBackupCreate(Request $request){
        try{
            
            set_time_limit(0);

            if($request->type == 'all'){
                Artisan::call('backup:run');
            }

            if($request->type == 'onlyfiles'){

                Artisan::call('backup:run --only-files');

            }

            if($request->type == 'onlydb'){

                Artisan::call('backup:run --only-db');

            }

        }catch(\Exception $e){
            return back()->with('error',$e->getMessage());
        }

        return back()->with('success','Backup completed !');

    }

    public function databaseBackupDelete($backup){
        $path = $this->get_path($backup);

        if (unlink($path)) {
            return back()->with('success','Database Backup deleted');
        }else{
            return back()->with('error','Database Backup not deleted');
        }
    } 
    
    private function get_path($name)
    {
        $name = $backups_folder . $name;

        if (file_exists($name . '.zip')) {
            // Laravel backup
            $path = $name . '.zip';
        } elseif (file_exists($name . '.sql.gz')) {
            // Prior to 2.3.2
            // From backup_manager
            $path = $name . '.sql.gz';
        } elseif (file_exists($name . '.sql')) {
            // Since 2.3.2
            $path = $name . '.sql';
        } else {
            $path = $name;
        }

        return $path;
    }

    public function databaseBackupDownload(Request $request, $filename){
        $filePath = storage_path().'/app/'.config('app.name').'/'.$filename;

        $fileContent = file_get_contents($filePath);

        $response = response($fileContent, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);

        return $response;
    }
}
