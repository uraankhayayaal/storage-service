<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\RemoveFileQuery;
use App\Http\Requests\UploadFileForm;
use App\Http\Requests\UploadFilesForm;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class UploaderService
{
    public const STORAGE = 'public'; // 'public'; // 's3', 'storage'

    /**
     * @throws FileNotFoundException
     */
    public function checkIsFileExist(string $fileUrl): bool
    {
        return Storage::disk(self::STORAGE)->exists(basename($fileUrl));
    }

    public function upload(UploadFileForm $uploadFileForm): string
    {
        $fileName = $this->generateFileName($uploadFileForm->uploadFile()->getClientOriginalName());

        $isUpload = Storage::disk(self::STORAGE)->put($fileName, $uploadFileForm->uploadFile());

        if ($isUpload === false) {
            throw new HttpException(500, "Error to save file to storage");
        }

        return Storage::url($fileName);
    }

    /**
     * @return array<string,null|string>
     */
    public function miltipleupload(UploadFilesForm $uploadFilesForm): array
    {
        $files = [];
        foreach ($uploadFilesForm->uploadFiles() as $file) {
            $fileName = $this->generateFileName($file->getClientOriginalName());

            $isUpload = Storage::disk(self::STORAGE)->put($fileName, $file);

            $files[$file->getClientOriginalName()] = $isUpload ? Storage::url($fileName) : null;
        }
        return $files;
    }

    public function remove(RemoveFileQuery $removeFileQuery): bool
    {
        $isDeleted = Storage::disk(self::STORAGE)->deleteDirectory(basename($removeFileQuery->url));

        if ($isDeleted === false) {
            throw new HttpException(500, "Error to remove file from storage");
        }

        return true;
    }

    private function generateFileName(string $fileOriginName): string
    {
        $originalFilenameArr = explode('.', $fileOriginName);

        $fileName = 'U-' . time() . '-' . str()->random() . '.' . end($originalFilenameArr);

        return $fileName;
    }
}
