<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
/**
 *
 * @OA\Schema(
 * @OA\Xml(name="Category"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="100500"),
 * @OA\Property(property="title", type="string"),
 * @OA\Property(property="slug_title", type="string"),
 * @OA\Property(property="picture", type="string"),
 * @OA\Property(property="preview_picture", type="string"),
 * @OA\Property(property="description", type="string"),
 * @OA\Property(property="created_at", type="string", example="2021-09-18T18:14:28.000000Z"),
 * @OA\Property(property="updated_at", type="string", example="2021-09-18T18:14:28.000000Z"),
 * )
 */
class Category extends Model
{
    use HasFactory;
    use NodeTrait;

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'category_post');
    }
}
