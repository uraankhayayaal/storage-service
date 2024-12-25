<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\UploadFileForm;
use App\Http\Requests\UploadFilesForm;
use App\Storages\StorageInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;

final class UploaderService
{
    public function __construct(
        private StorageInterface $storage,
    ) {}

    /**
     * @throws FileNotFoundException
     */
    public function checkIsFileExist(string $fileUrl): bool
    {
        return $this->storage->checkIsFileExist($fileUrl);
    }

    public function upload(UploadFileForm $uploadFileForm): ?string
    {
        $fileName = $this->generateFileName($uploadFileForm->file('file'));

        return $this->storage->putFile($uploadFileForm->file('file'), $fileName);
    }

    /**
     * @return array<string,null|string>
     */
    public function miltipleupload(UploadFilesForm $uploadFilesForm): array
    {
        $files = [];
        foreach ($uploadFilesForm->file() as $file) {
            $files[$this->generateFileName($file)] = $file;
        }

        return $this->storage->putFiles($files);
    }

    public function remove(string $fileUrl): bool
    {
        return $this->storage->removeFile($fileUrl);
    }

    private function generateFileName(UploadedFile $file): string
    {
        $originalFilenameArr = explode('.', $file->getClientOriginalName());

        $fileName = 'U-' . time() . '-' . str()->random() . '.' . end($originalFilenameArr);

        return $fileName;
    }
}
