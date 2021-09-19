<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 *
 * @OA\Schema(
 * @OA\Xml(name="Post"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="100500"),
 * @OA\Property(property="title", type="string"),
 * @OA\Property(property="title_slug", type="string"),
 * @OA\Property(property="picture", type="string"),
 * @OA\Property(property="preview_picture", type="string"),
 * @OA\Property(property="text", type="string"),
 * @OA\Property(property="preview_text", type="string"),
 * @OA\Property(property="author_id", type="integer"),
 * @OA\Property(property="category_ids", type="array", @OA\Items(type="integer")),
 * @OA\Property(property="created_at", type="string", example="2021-09-18T18:14:28.000000Z"),
 * @OA\Property(property="updated_at", type="string", example="2021-09-18T18:14:28.000000Z"),
 * )
 */
class Post extends Model
{
    use HasFactory;

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }
}
