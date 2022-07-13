<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->delete();
        
        // \DB::table('roles')->insert(array (
        //     0 => 
        //     array (
        //         'id' => 1,
        //         'name' => 'Admin',
        //         'guard_name' => 'admin',
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ),
        //     1 => 
        //     array (
        //         'id' => 2,
        //         'name' => 'Super Stockiest',
        //         'guard_name' => 'super_stockist',
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ),
        //     2 => 
        //     array (
        //         'id' => 3,
        //         'name' => 'C & F',
        //         'guard_name' => 'c_and_f',
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ),
        //     3 => 
        //     array (
        //         'id' => 4,
        //         'name' => 'Mother Depo',
        //         'guard_name' => 'mother_depo',
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ),
        //     4=>
        //     array (
        //         'id' => 5,
        //         'name' => 'Distributor',
        //         'guard_name' => 'distributor',
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ),
        // ));
    }
}
