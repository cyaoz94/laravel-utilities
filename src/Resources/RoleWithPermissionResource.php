<?php

namespace Cyaoz94\LaravelUtilities\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleWithPermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'permissions' => $this->permissions->pluck('name'),
        ];
    }
}
