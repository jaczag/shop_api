<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int|null external_id
 * @property string|null title
 * @property int price
 * @property int|null $category_id
 * @property boolean is_active
 * @property string|null description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Product extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'products';

    /**
     * @var string[]
     */
    protected $fillable = [
        'external_id',
        'title',
        'price',
        'category_id',
        'is_active',
        'description',
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
            return $this->belongsTo(Category::class);
    }
}
