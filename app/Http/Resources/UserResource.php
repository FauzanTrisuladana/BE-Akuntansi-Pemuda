<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class UserResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenHas('id'),
            'name' => $this->whenHas('name'),
            'email' => $this->whenHas('email'),
            'role' => $this->whenHas('role'),
            'status' => $this->whenHas('status'),
            'profile_image' => $this->whenHas('profile_image'),
            'has_password' => $this->whenHas('has_password'),
        ];
    }
}
