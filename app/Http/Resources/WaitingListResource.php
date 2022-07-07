<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class WaitingListResource extends JsonResource
{
    public static $wrap = 'waitingList';

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'      => $this->id,
            'cake_id' => $this->cake_id,
            'name'    => $this->name,
            'email'   => $this->email,
            //            'cake'  => new CakeResource($this->cake)
        ];
    }
}
