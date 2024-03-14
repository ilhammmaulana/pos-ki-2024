<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionRelationResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'total' => format_rupiah($this->total),
            'pay' => format_rupiah($this->pay),
            'createdBy' => new UserViewResource($this->createdBy),
            'customer' => new CustomerResource($this->customer),
            'detailTransaction' => TransactionDetailResource::collection($this->detailTransaction),
            'code_promo' => $this->code_promo,
            'created_at' => $this->created_at->format('Y-m-d H:m:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:m:s')
        ];
    }
}
