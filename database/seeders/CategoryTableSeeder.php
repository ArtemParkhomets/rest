<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $filepath = storage_path('app/public/categories');
        if(!File::exists($filepath)){
            File::makeDirectory($filepath);
        }
        try {
            $file = $faker->image($filepath,200,200, null, false);
            Image::make($filepath.'/'.$file)
                ->resize(100, 100)
                ->save($filepath.'/100_100_'.$file);
        } catch (\Throwable $exception) {
            $file = 'image.img';
        }
        for($i = 0; $i < 5; $i++) {
            $title = $faker->words(3, true);
            $fields = [
                'title' => $title,
                'slug_title' => Str::slug($title),
                'description' => $faker->text($maxNbChars = 200),
                'picture' => $file,
            ];
            Category::create($fields);
            $parent = Category::get()->last();
            for($j = 0; $j < 5; $j++) {
                $title = $faker->words(3, true);
                $fields = [
                    'title' => $title,
                    'slug_title' => Str::slug($title),
                    'description' => $faker->text($maxNbChars = 200),
                    'picture' => $file,
                ];
                Category::create($fields, $parent);
                $parent = Category::get()->last();
                for($k = 0; $k < 5; $k++) {
                    $title = $faker->words(3, true);
                    $fields = [
                        'title' => $title,
                        'slug_title' => Str::slug($title),
                        'description' => $faker->text($maxNbChars = 200),
                        'picture' => $file,
                    ];
                    Category::create($fields, $parent);
                }
            }
        }
    }
}
