<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{
    protected ?string $message = 'Berhasil';

    /**
     * Set custom success message for this resource response.
     */
    public function message(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Add meta that always exists on the top-level response.
     */
    public function with(Request $request): array
    {
        $meta = [
            'status' => 'success',
        ];

        if ($this->message !== null) {
            $meta['message'] = $this->message;
        }

        return $meta;
    }

    /**
     * Ensure `status` and `message` appear before `data` on the top-level response.
     */
    public function toResponse($request)
    {
        $response = parent::toResponse($request);
        $payload = $response->getData(true);

        $meta = $this->with($request);
        $ordered = array_merge($meta, array_diff_key($payload, $meta));

        return $response->setData($ordered);
    }

    /**
     * Return a collection that also includes `status` and `message`.
     *
     * @param  \Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator|array  $resource
     * @return ApiResourceCollection
     */
    public static function collection($resource)
    {
        return new ApiResourceCollection($resource, static::class);
    }
}
