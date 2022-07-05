<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CakeResource extends JsonResource
{
    public static $wrap = 'cake';

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        return parent::toArray($request);

        return [
            'id'                 => $this->id,
            'description'        => "Wonderful '{$this->name}' cake, worth \${$this->value} and weighing {$this->weight}{$this->unit_of_measure}.",
            'name'               => $this->name,
            'weight'             => $this->weight,
            'value'              => $this->value,
            'available_quantity' => $this->available_quantity,
            'unit_of_measure'    => $this->unit_of_measure
        ];
    }
}
