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
            'status' => $this->status,
            'customer' => new CustomerResource($this->customer),
            'total_price' => format_rupiah($this->total_price),
            'customer_money' => format_rupiah($this->customer_money),
            'return_money' => format_rupiah($this->return_money),
            'details' => TransactionDetailResource::collection($this->details),
            'created_by' => $this->created_by,
            'created_at' => $this->created_at->format('Y-m-d H:m:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:m:s')
        ];
    }
}
