<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property StoreProduct $products
 */
class Section extends Model
{
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            StoreProduct::class,
            'store_products_section',
            'section_id',
            'store_product_id',
            'id',
            'id'
        )
        ->withPivot('position');
      /*  $todo this is ambiguous ->orderBy('position', 'ASC');*/
    }
}
