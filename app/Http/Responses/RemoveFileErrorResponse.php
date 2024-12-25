<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RemoveFileErrorResponse extends JsonResource
{
    public int $removedAt;

    public function __construct(
        public string $status = 'error',
    ) {
        $this->removedAt = time();
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'removedAt' => $this->removedAt,
        ];
    }
}
