<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ScreeningResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->toDateTimeString(),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'username' => $this->user->username,
                'erp_id' => $this->user->erp_id,
                'infinias_id' => $this->user->infinias_id
            ]
        ];
    }
}
