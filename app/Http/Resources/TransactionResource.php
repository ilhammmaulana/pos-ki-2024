<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'id' => $this->id,
            'total' => $this->total,
            'customer_id' => $this->customer_id,
            'total' => format_rupiah($this->total),chr
            'pay' => format_rupiah($this->pay),
            'createdBy' => $this->createdBy,
            'customer' => $this->customer,
            'code_promo' => $this->code_promo,
            'created_at' => $this->created_at->format('Y-m-d H:m:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:m:s')
        ];
    }
}
