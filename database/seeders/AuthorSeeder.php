<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $filepath = storage_path('app/public/avatars');
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
        for($i = 1; $i < 50; $i++) {
            $name = $faker->name();
            $fields[] = [
                'full_name'  => $name,
                'slug_name'  => Str::slug($name),
                'avatar'     => $file,
                'biography'  => $faker->realText(500),
                'year_birth' => rand(1000, 2000),
            ];
        }
        Author::insert($fields);

    }
}
