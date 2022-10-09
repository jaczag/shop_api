<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int external_id
 * @property string title
 * @property int price
 * @property string category
 * @property boolean is_active
 * @property string description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Product extends Model
{
    use HasFactory;
}
