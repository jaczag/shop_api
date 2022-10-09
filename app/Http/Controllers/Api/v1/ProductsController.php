<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StoreProductRequest;
use App\Http\Requests\v1\UpdateProductRequest;
use App\Http\Resources\v1\ProductCollection;
use App\Http\Resources\v1\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProductsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $products = Product::
        with('category')
            ->when($filter = Arr::get($request, 'filter'), function (Builder $query) use ($filter) {
                return $query
                    ->whereHas('category', function (Builder $query) use ($filter) {
                        return $query
                            ->where('name', $filter);
                    });
            })
            ->orderBy(
                Arr::get($request, 'sortBy', 'id'),
                Arr::get($request, 'orderBy', 'DESC')
            )
            ->paginate(Arr::get($request, 'perPage', 10));

        return $this->successResponse(ProductCollection::make($products));
    }

    /**
     * @param StoreProductRequest $request
     * @param ProductService $service
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request, ProductService $service): JsonResponse
    {
        $data = $request->validated();

        try {
            $service->assignData($data)->saveProduct();
            return $this->successResponse();
        } catch (Exception $exception) {
            reportError($exception);
        }
        return $this->errorResponse(
            __("Something went wrong.")
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        return $this->successResponse(ProductResource::make($product));
    }

    /**
     * @param UpdateProductRequest $request
     * @param Product $product
     * @param ProductService $service
     * @return JsonResponse
     */
    public function update(UpdateProductRequest $request, Product $product, ProductService $service): JsonResponse
    {
        $data = $request->validated();
        $product = $service->setProduct($product)
            ->updateProduct($data)
            ->saveProduct()
            ->getProduct();

        return $this->successResponse(ProductResource::make($product));
    }

}
