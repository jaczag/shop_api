<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    /**
     * @var Category|null
     */
    private ?Category $category;

    /**
     * @param Category|null $category
     */
    public function __construct(?Category $category = null)
    {
        $this->category = $category ?? new Category();
    }

    /**
     * @param array $data
     * @return $this
     */
    public function assignData(array $data): static
    {
        $this->category->name = $data['name'];
        return $this;
    }

    /**
     * @return $this
     */
    public function saveCategory(): static
    {
        $this->category->save();
        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function setCategory(Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @param string|int $attribute
     * @return $this
     */
    public function findByAttr(string|int $attribute): static
    {
        $q = Category::query();
        $this->category = is_int($attribute) ? $q->find($attribute) : $q->where('name', $attribute)->first();

        return $this;
    }

    /**
     * @return $this
     */
    public function newCategory(): static
    {
        $this->category = new Category();
        return $this;
    }
}
