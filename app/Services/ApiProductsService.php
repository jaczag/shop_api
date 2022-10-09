<?php

namespace App\Services;

use App\Http\Integrations\ShopApi\Requests\FetchProductsRequest;
use App\Models\Product;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use ReflectionException;
use Sammyjo20\Saloon\Exceptions\SaloonException;
use stdClass;

class ApiProductsService
{
    private int $limit;
    private int $skip;
    private array $products = [];

    public function __construct(int $limit = 10) {
        $this->limit = $limit;
        $this->skip = Cache::get('products_to_skip') ?? 0;
    }

    /**
     * @return void
     */
    private function updateCache(): void
    {
        Cache::forever('products_to_skip', $this->skip + $this->limit);
    }


    /**
     * @throws ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function fetchProducts(): static
    {
        $request  = new FetchProductsRequest($this->limit, $this->skip);
        $response = $request->send()->object();
        $this->products = $response->products;
        return $this;
    }

    /**
     * @return void
     */
    public function saveProducts(): void
    {
        foreach($this->products as $product) {
            Product::create($product);
        }
        $this->updateCache();
    }
}
