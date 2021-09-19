<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AuthorResource extends JsonResource
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
            'id'               => $this->id,
            'full_name'        => $this->full_name,
            'slug_name'        => $this->slug_name,
            'year_birth'       => $this->year_birth,
            'biography'        => $this->biography,
            'avatar'           => Storage::url(storage_path('app/public/avatars/') . $this->avatar),
            'preview_avatar'  => Storage::url(storage_path('app/public/avatars/100_100_') . $this->avatar),
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
