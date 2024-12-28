<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UploadFilesSuccessResponse extends JsonResource
{
    /**
     * @param array<string,null|string> $urls
     */
    public function __construct(
        public array $urls,
    ) {}

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'urls' => $this->urls,
        ];
    }
}
