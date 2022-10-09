<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\category\StoreCategoryRequest;
use App\Http\Requests\v1\category\UpdateCategoryRequest;
use App\Http\Resources\v1\CategoryCollection;
use App\Http\Resources\v1\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->orderBy(
                Arr::get($request, 'sortBy', 'id'),
                Arr::get($request, 'orderBy', 'DESC')
            )
            ->paginate(Arr::get($request, 'perPage', 10));

        return $this->successResponse(CategoryCollection::make($categories));
    }

    /**
     * @param \App\Http\Requests\v1\category\StoreCategoryRequest $request
     * @param CategoryService $service
     * @return JsonResponse
     */
    public function store(
        StoreCategoryRequest $request,
        CategoryService $service
    ): JsonResponse {
        $data = $request->validated();

        try {
            $category = $service->assignData($data)->saveCategory()->getCategory();
            return $this->successResponse(CategoryResource::make($category));
        } catch (Exception $exception) {
            reportError($exception);
        }

        return $this->errorResponse(
            __("Something went wrong.")
        );
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        return $this->successResponse(CategoryResource::make($category));
    }

    /**
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @param CategoryService $service
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category, CategoryService $service): JsonResponse
    {
        $data = $request->validated();

        try {
            $category = $service->setCategory($category)
                ->assignData($data)
                ->saveCategory()
                ->getCategory();

            return $this->successResponse(CategoryResource::make($category));
        } catch (Exception $exception) {
            reportError($exception);
        }
        return $this->errorResponse(
            __('messages.Something went wrong.')
        );
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        Auth::check();

        try {
            $category->delete();

            return $this->successResponse();
        } catch (Exception $exception) {
            reportError($exception);
        }

        return $this->errorResponse(
            __('messages.Something went wrong.')
        );
    }
}
