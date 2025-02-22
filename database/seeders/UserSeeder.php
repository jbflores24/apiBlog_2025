<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name'=>'José Braulio Flores Mtz.',
            'email' => 'jbflores24@hotmail.com',
            'password' => '1234',
            'rol_id'=>1,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);
        DB::table('users')->insert([
            'name'=>'Novaly Briannet flores Salazar',
            'email' => 'novaly@hotmail.com',
            'password' => '1234',
            'rol_id'=>2,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);
    }
}
