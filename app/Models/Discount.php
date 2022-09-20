<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /**
     * Adding a Discount model seems the right way to go if this was a real application; we
     *   wouldn't want to hardcode the discounts into our Product model for very long.
     * 
     * Note: the is_active property isn't in the requirements but I've added it because it would
     *   almost certainly be necessary in a real world scenario. I added a global scope to always
     *   check that it's true in queries so there are no headaches.
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }
    
    public function discountable()
    {
        return $this->morphTo();
    }

    public function scopeWithInactive($query)
    {
        return $query->orWhere('is_active', false);
    }
}
