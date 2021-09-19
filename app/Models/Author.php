<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 *
 * @OA\Schema(
 * @OA\Xml(name="Author"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="100500"),
 * @OA\Property(property="full_name", type="string"),
 * @OA\Property(property="slug_name", type="string"),
 * @OA\Property(property="avatar", type="string"),
 * @OA\Property(property="preview_avatar", type="string"),
 * @OA\Property(property="year_birth", type="integer"),
 * @OA\Property(property="biography", type="string"),
 * @OA\Property(property="created_at", type="string", example="2021-09-18T18:14:28.000000Z"),
 * @OA\Property(property="updated_at", type="string", example="2021-09-18T18:14:28.000000Z"),
 * )
 */
class Author extends Model
{
    use HasFactory;

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }
}
