<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    private Category $category;

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
     * @return Category
     */
    public function getCategory(): Category
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
}
