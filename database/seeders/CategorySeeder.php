<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    private $category;
    public function __construct(Category $category){
        $this->category = $category;
    }

    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fashion',
                'created_at' => NOW(),
                'updated_at' => NOW()
            ],
            [
                'name' => 'Gadgets',
                'created_at' => NOW(),
                'updated_at' => NOW()
            ],
            [
                'name' => 'Education',
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]
        ];
        $this->category->insert($categories);
    }
}
