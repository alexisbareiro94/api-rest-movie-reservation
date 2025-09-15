<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Fantasy',
            'Horror',
            'Mystery',
            'Romance',
            'Science Fiction',
            'Thriller',
            'Western',
            'Animation',
            'Documentary',
            'Crime',
            'Family',
            'Musical',
            'War',
            'Biography',
            'History',
            'Sport'
        ];

        foreach($categories as $category){
            DB::table('categories')->insert([
                'name' => $category,
            ]);
        }

        // DB::table('categories')->insert([
        //     'name' => 'terror'
        // ]);
    }
}
