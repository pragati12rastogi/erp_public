<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Custom\Constants;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();
        
        
        $user = User::create(
            ['name' => 'SuperAdmin',
            'profile'=>null,
            'firm_name'=>'XYZ',
            'role_id'=> 1,
            'address' =>'somewhere',
            'gst_no' =>'Abcd123456789',
            'mobile' =>'9793749498',
            'state_id' =>10,
            'district'=>'Delhi',
            'email'=>'admin@erp.com',
            'email_verified_at'=>null,
            'password' => bcrypt(12345678),
            'status' => '1',
            'bank_name' => 'Test Bank',
            'name_on_passbook' => 'Test User',
            'ifsc' => '76823test',
            'account_no' => '8398209382019',
            'pan_no' => 'test398e928000',

            ]
        );
        
        
        $role = Role::create(['name'=>'Admin']);
        

        $permissions = Permission::pluck('id','id')->all();
   
        $role->syncPermissions($permissions);
        
        $user->assignRole([$role->id]);
    }
}
