<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property float $price
 * @property string $category
 * @property int $stock
 * @property bool $popular
 * @property string|null $description
 * @property string|null $image
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'category',
        'stock',
        'popular',
        'description',
        'image',
        'variants',
    ];

    protected $casts = [
        'popular' => 'boolean',
        'price' => 'decimal:2',
        'variants' => 'array',
    ];

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}