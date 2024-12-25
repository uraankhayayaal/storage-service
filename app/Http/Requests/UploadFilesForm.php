<?php

declare(strict_types=1);

namespace App\Http\Requests;

use OpenApi\Attributes as OA;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Uraankhayayaal\OpenapiGeneratorLumen\Http\Requests\BaseRequestFormData;

final class UploadFilesForm extends BaseRequestFormData
{
    /** @var mixed[] */
    #[OA\Property(property: 'file', type: 'array', items: new OA\Items(type: 'string', format: 'binary'), nullable: false)]
    public array $files;

    public function rules(): array
    {
        return [
            'files' => 'required',
            'files.*' => 'file',
        ];
    }

    public function messages(): array
    {
        return [
            'files.required' => 'Files is required',
        ];
    }

    /**
     * @return \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|array|null
     */
    public function file(): array
    {
        $uploadFiles = app('request')->file('files');

        if ($uploadFiles === null) {
            return [];
        }

        if ($uploadFiles instanceof \Illuminate\Http\UploadedFile) {
            return [$uploadFiles];
        }

        if (is_array($uploadFiles)) {
            return $uploadFiles;
        }

        $validator = Validator::make($this->files, []);
        $validator->errors()->add('files', 'Error of input files, please try to pick other files');

        throw new ValidationException($validator);
    }
}
