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
            "id" => $this->id,
            "name" => $this->name,
            "price" => format_rupiah($this->sell_price),
            "stock" => $this->stock,
            "image" => url($this->image),
            "category" => $this->category,
            'created_at' => $this->created_at->format('Y-m-d H:m:s'),
        ];
    }
}
