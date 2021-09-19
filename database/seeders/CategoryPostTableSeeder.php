<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryPost;
use App\Models\Post;
use Illuminate\Database\Seeder;

class CategoryPostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::all()->pluck('id')->toArray();
        $categories = Category::all()->pluck('id')->toArray();
        for($i= 0; $i < 200; $i++) {
            $fields[] = [
                'category_id' => $categories[array_rand($categories, 1)],
                'post_id' => $posts[array_rand($posts, 1)],
            ];
        }
        CategoryPost::insert($fields);
    }
}
