<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'app_user_id' => '7',
            'stripe_id' => 'cus_xxx',
        ]);
    }
}
