<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrUser=[
            [
                'name'=> 'Super Admin',
                'email'=> 'superadmin@gmail.com',
                'password'=> Hash::make('1234'),
            ],
            [
                'name'=> ' Admin',
                'email'=> 'admin@gmail.com',
                'password'=> Hash::make('1234'),
            ],
            [
                'name'=> ' Jibon Ahamed',
                'email'=> 'jibon@gmail.com',
                'password'=> Hash::make('1234'),
            ]
        ];
        User::truncate();
        foreach ($arrUser as $eachuser){
            $use = new User();
            $use->fill($eachuser);
            $use->save();
        }
    }
}