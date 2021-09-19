<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $author_ids = Author::pluck('id')->toArray();
        $filepath = storage_path('app/public/posts');
        if(!File::exists($filepath)){
            File::makeDirectory($filepath);
        }
        $file = $faker->image($filepath,300,300, null, false);
        Image::make($filepath.'/'.$file)
            ->resize(100, 100)
            ->save($filepath.'/100_100_'.$file);

        for($i = 0; $i < 10000; $i++) {
            $title = $faker->sentence();
            $fields[] = [
                'title' => $title,
                'title_slug' => Str::slug($title),
                'picture' => $file,
                'preview_text' => $faker->sentences('3', true),
                'text' => $faker->sentences('5', true),
                'author_id' => $author_ids[array_rand($author_ids)],
            ];
        }
        Post::insert($fields);
    }
}
