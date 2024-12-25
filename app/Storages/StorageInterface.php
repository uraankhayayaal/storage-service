<?php

declare(strict_types=1);

namespace App\Storages;

use Illuminate\Http\UploadedFile;

interface StorageInterface
{
    public function putFile(UploadedFile $file, string $fileName): ?string;

    /**
     * @param array<string,UploadedFile> $files
     *
     * @return array<string,null|string>
     */
    public function putFiles(array $files): array;

    public function checkIsFileExist(string $fileUrl): bool;

    public function removeFile(string $fileUrl): bool;
}
