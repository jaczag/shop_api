<?php

namespace App\Http\Resources\v1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'title' => $this->title,
            'price' => $this->price,
            'category' => $this->category,
            'is_active' => $this->is_active,
            'description' => $this->description,
            'created_at' => timestampToString($this->created_at),
            'updated_at' => timestampToString($this->updated_at),
        ];
    }
}
