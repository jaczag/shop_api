<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Str;

class CartService
{
    /**
     * @var Cart|null
     */
    private ?Cart $cart;

    public function __construct(?Cart $cart = null)
    {
        $this->cart = $cart;
    }

    /**
     * @param string|null $cart_token
     * @return $this
     */
    public function setCart(?string $cart_token = null): static
    {
        $this->cart = Cart::firstOrNew([
            'token' => $cart_token ?? Str::uuid(),
        ]);

        return $this;
    }

    /**
     * @param int|null $user_id
     * @return $this
     */
    public function addUser(?int $user_id = null): static
    {
        $this->cart->user_id = $user_id;
        return $this;
    }

    /**
     * @return $this
     */
    public function saveCart(): static
    {
        $this->cart->save();
        return $this;
    }

    /**
     * @param array $ids
     * @return $this
     */
    public function addProducts(array $ids): static
    {
        $this->cart->products()->attach($ids);
        return  $this;
    }

    /**
     * @return Cart
     */
    public function getCart(): Cart
    {
        return $this->cart;
    }
}
