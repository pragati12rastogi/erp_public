<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        \DB::table('states')->delete();
        
        \DB::table('states')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Andaman and Nicobar Islands',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Andhra Pradesh',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Arunachal Pradesh',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Assam',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Bihar',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Chandigarh',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Chhattisgarh',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Dadra and Nagar Haveli',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Daman and Diu',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Delhi',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Goa',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Gujarat',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Haryana',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Himachal Pradesh',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Jammu and Kashmir',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Jharkhand',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Karnataka',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Kenmore',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Kerala',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Lakshadweep',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Madhya Pradesh',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Maharashtra',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Manipur',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'Meghalaya',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'Mizoram',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'Nagaland',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'Narora',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'Natwar',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'Odisha',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'Paschim Medinipur',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'Pondicherry',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'Punjab',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'Rajasthan',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'Sikkim',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'Tamil Nadu',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'Telangana',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'Tripura',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'Uttar Pradesh',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'Uttarakhand',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'Vaishali',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'West Bengal',
            )
        ));
    }
}
