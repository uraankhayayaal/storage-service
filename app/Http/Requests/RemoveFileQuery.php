<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Uraankhayayaal\OpenapiGeneratorLumen\Http\Requests\BaseRequestQueryData;

final class RemoveFileQuery extends BaseRequestQueryData
{
    public string $url;

    public function rules(): array
    {
        return [
            'url' => 'required|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'url.required' => 'Url is required',
        ];
    }
}
