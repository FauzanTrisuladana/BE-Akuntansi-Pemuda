<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiResourceCollection extends AnonymousResourceCollection
{
    protected ?string $message = 'Berhasil';

    /**
     * Set custom success message for this collection response.
     */
    public function message(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Add meta that always exists on the top-level response.
     *
     * @return array<string, mixed>
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
     * Add pagination information to the response if the collection is paginated.
     *
     * @param  array<string, mixed>  $paginated
     * @param  array<string, mixed>  $default
     * @return array<string, mixed>
     */
    public function paginationInformation(Request $request, array $paginated, array $default): array
    {
        return [
            'meta' => [
                'current_page' => $paginated['current_page'],
                'last_page' => $paginated['last_page'],
                'per_page' => $paginated['per_page'],
                'total' => $paginated['total'],
            ],
        ];
    }
}
