<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this['id'],
            'publisher' => $this['publisher'],
            'title' => $this['title'],
            'summary' => $this['summary'],
            'authors' => $this['authors'],
        ];
    }
}
