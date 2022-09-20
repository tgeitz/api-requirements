<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    

    public function mostRelevantDiscountPercentage()
    {
        $allDiscounts = $this->discounts->concat($this->category->discounts);

        return $allDiscounts->where('is_active', true)->max('discount_percentage');
    }

    public function discountedPrice()
    {
        $mostRelevantDiscountPercentage = $this->mostRelevantDiscountPercentage();
        return intval($mostRelevantDiscountPercentage ? ($this->price * ((100 - $mostRelevantDiscountPercentage) / 100)) : $this->price);
    }
}
