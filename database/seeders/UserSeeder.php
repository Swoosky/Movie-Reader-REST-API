<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //GD 11
        DB::table('users')->insert([
            'name' => 'Agush',
            'email' => '10193@students.uajy.ac.id',
            'password' => '$2b$10$OaXoZ/NX/xqRdWpWk435teBFZfbqZxepxR5Vth6y/FWxPaRNLiBau',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
