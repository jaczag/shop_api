<?php

namespace App\Http\Resources\v1;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 * @property mixed $id
 */
class CategoryResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => timestampToString($this->created_at),
            'updated_at' => timestampToString($this->updated_at),
        ];
    }
}
