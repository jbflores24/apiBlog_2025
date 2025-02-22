<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('articles')->insert([
            'titulo' => 'Artículo 1',
            'imagen' => 'img1.jpg',
            'texto' => 'Texto del artículo 1',
            'user_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('articles')->insert([
            'titulo' => 'Artículo 2',
            'imagen' => 'img2.jpg',
            'texto' => 'Texto del artículo 2',
            'user_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('articles')->insert([
            'titulo' => 'Artículo 3',
            'imagen' => 'img3.jpg',
            'texto' => 'Texto del artículo 3',
            'user_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('articles')->insert([
            'titulo' => 'Artículo 4',
            'imagen' => 'img4.jpg',
            'texto' => 'Texto del artículo 4',
            'user_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
