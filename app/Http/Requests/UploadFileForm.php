<?php

declare(strict_types=1);

namespace App\Http\Requests;

use OpenApi\Attributes as OA;
use Uraankhayayaal\OpenapiGeneratorLumen\Http\Requests\BaseRequestFormData;

final class UploadFileForm extends BaseRequestFormData
{
    /** @var mixed */
    #[OA\Property(property: 'file', type: 'string', format: 'binary', nullable: false)]
    public $file;

    public function rules(): array
    {
        return [
            'file' => 'required|file',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'File is required',
        ];
    }

    /**
     * @return \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|array|null
     */
    public function uploadFile(): mixed
    {
        return app('request')->file('file');
    }
}
