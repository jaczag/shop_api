<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\cart\StoreCartRequest;
use App\Http\Requests\v1\category\StoreCategoryRequest;
use App\Http\Resources\v1\CartResource;
use App\Services\CartService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CartsController extends Controller
{
    /**
     * @param StoreCartRequest $request
     * @param CartService $service
     * @return JsonResponse
     */
    public function store(StoreCartRequest $request, CartService $service): JsonResponse
    {
        $data = $request->validated();

        try {
            $cart = $service->setCart(Arr::get($data, 'cart_token'))
                ->addUser(Arr::get($data, 'user_id'))
                ->saveCart()
                ->addProducts($data['products_ids'])
                ->getCart();

            return $this->successResponse(CartResource::make($cart));
        } catch (Exception $exception) {
            reportError($exception);
        }

        return $this->errorResponse(
            __('messages.Something went wrong.')
        );
    }
}
