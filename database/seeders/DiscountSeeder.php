<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $discounts = [
            ['discountable_type' => 'App\Models\Product', 'discountable_id' => Product::where('sku', '000003')->firstOrFail()->id, 'discount_percentage' => 15, 'is_active' => true],
            ['discountable_type' => 'App\Models\Category', 'discountable_id' => Category::where('name', 'insurance')->firstOrFail()->id, 'discount_percentage' => 30, 'is_active' => true],
        ];

        foreach ($discounts as $discount) {
            Discount::create([
                'discountable_type' => $discount['discountable_type'],
                'discountable_id' => $discount['discountable_id'],
                'discount_percentage' => $discount['discount_percentage'],
                'is_active' => $discount['is_active'],
            ]);
        }
    }
}
