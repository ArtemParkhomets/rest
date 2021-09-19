<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'title_slug'      => $this->title_slug,
            'text'            => $this->text,
            'preview_text'    => $this->preview_text,
            'author_id'       => $this->author_id,
            'picture'         => Storage::url(storage_path('app/public/posts/') . $this->picture),
            'preview_picture' => Storage::url(storage_path('app/public/posts/100_100_') . $this->picture),
            'category_ids'    => $this->categories->pluck('id'),
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
