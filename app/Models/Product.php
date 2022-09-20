<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }
    
    /**
     * Rather than applying all discounts multiplicatively, we'll apply the most relevant one--
     *   which I've taken to mean the highest applicable discount, for the customer's benefit.
     */
    public function mostRelevantDiscountPercentage()
    {
        $allDiscounts = $this->discounts->concat($this->category->discounts);

        return $allDiscounts->max('discount_percentage');
    }

    public function discountedPrice()
    {
        $mostRelevantDiscountPercentage = $this->mostRelevantDiscountPercentage();
        return intval($mostRelevantDiscountPercentage ? ($this->price * ((100 - $mostRelevantDiscountPercentage) / 100)) : $this->price);
    }
}
