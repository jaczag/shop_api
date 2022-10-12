<?php

namespace App\Http\Resources\v1;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Cart
 */
class CartResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'token' => $this->token,
            'user' => UserResource::make($this->user),
            'created_at' => timestampToString($this->created_at),
            'updated_at' => timestampToString($this->updated_at),
            'total' => $this->products->sum('price'),
            'products' => ProductResource::collection($this->products),
        ];
    }
}

