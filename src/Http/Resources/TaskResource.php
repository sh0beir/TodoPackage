<?php

namespace sh0beir\todo\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->completed,
            'created_at' => $this->created_at,
            'labels' => LabelResource::collection($this->whenLoaded('labels')),
        ];
    }
}
