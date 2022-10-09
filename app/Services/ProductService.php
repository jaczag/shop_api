<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Arr;

class ProductService
{
    private Product $product;

    /**
     * @param Product|null $product
     */
    public function __construct(?Product $product = null)
    {
        $this->product = $product ?? new Product();
    }

    /**
     * @param array $data
     * @return $this
     */
    public function assignData(array $data): static
    {
        $this->product->external_id = Arr::get($data, 'external_id');
        $this->product->title = Arr::get($data, 'title');
        $this->product->price = Arr::get($data, 'price', 0);
        $this->product->category_id = Arr::get($data, 'category_id');
        $this->product->is_active = Arr::get($data, 'is_active', 0);
        $this->product->description = Arr::get($data, 'description');

        return $this;
    }

    /**
     * @return $this
     */
    public function saveProduct(): static
    {
        $this->product->save();
        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product): static
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function updateProduct($data): static
    {
        foreach ($data as $key => $value) {
            $this->product->$key = $value;
        }

        return $this;
    }
}
