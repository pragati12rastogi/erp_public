<?php

namespace App\Custom;

class Constants
{
    const ROLE_ADMIN = 'Admin';
    
    public static function backups_folder(){
        return storage_path()."/app/".config('app.name')."/";
    }
    
}