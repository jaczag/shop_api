<?php

namespace App\Http\Integrations\ShopApi\Requests;

use App\Http\Integrations\ShopApi\ProductsApiConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;

class FetchProductsRequest extends SaloonRequest
{
    /**
     * The connector class.
     *
     * @var string|null
     */
    protected ?string $connector = ProductsApiConnector::class;

    /**
     * The HTTP verb the request will use.
     *
     * @var string|null
     */
    protected ?string $method = Saloon::GET;

    /**
     * The endpoint of the request.
     *
     * @return string
     */
    public function defineEndpoint(): string
    {
        return "/products";
    }

    /**
     * @param int $limit
     * @param int $skip
     */
    public function __construct(public int $limit = 10, public int $skip = 0)
    {
    }

    /**
     * @return array
     */
    public function defaultQuery(): array
    {
        return [
          'limit' => $this->limit,
          'skip'  => $this->skip,
        ];
    }
}
