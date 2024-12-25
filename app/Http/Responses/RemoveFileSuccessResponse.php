<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RemoveFileSuccessResponse extends JsonResource
{
    public int $createdAt;

    public function __construct(
        public string $status = 'success',
    ) {
        $this->createdAt = time();
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
            'createdAt' => $this->createdAt,
        ];
    }
}
