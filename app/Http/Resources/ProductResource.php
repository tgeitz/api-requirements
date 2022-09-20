<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'category' => $this->category->name,
            'price' => [
                'original' => $this->price,
                'final' => $this->discountedPrice(),
                'discount_percentage' => $this->mostRelevantDiscountPercentage() > 0 ? $this->mostRelevantDiscountPercentage() . '%' : null,
                'currency' => 'EUR',
            ],
        ];
    }
}
