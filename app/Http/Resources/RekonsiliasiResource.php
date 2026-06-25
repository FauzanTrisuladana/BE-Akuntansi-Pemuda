<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class RekonsiliasiResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tanggal' => isset($this->tanggal)
                ? (is_string($this->tanggal) ? $this->tanggal : $this->tanggal->format('Y-m-d'))
                : null,
            'sistem' => $this->sistem ?? 0,
            'riil' => $this->riil ?? 0,
            'verified' => $this->verified ?? false,
        ];
    }
}
