<?php

declare(strict_types=1);

namespace App\Storages;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class LocalStorage implements StorageInterface
{
    private const PATH = '/upload/';

    private string $path;
    private string $url;

    public function __construct()
    {
        $this->path = app()->basePath() . '/public' . self::PATH;
        $this->url = self::PATH;
    }

    public function putFile(UploadedFile $file, string $fileName): ?string
    {
        try {
            $origin = $file->move($this->path, $fileName);
        } catch (FileException $exception) {
            Log::error($exception->getMessage());
            return null;
        }

        return $this->url . $origin->getFilename();
    }

    /**
     * @param array<string,UploadedFile> $files
     *
     * @return array<string,null|string>
     */
    public function putFiles(array $files): array
    {
        $filesUploadStatuses = [];
        foreach ($files as $fileName => $file) {
            $filesUploadStatuses[$file->getClientOriginalName()] = $this->putFile($file, $fileName);
        }

        return $filesUploadStatuses;
    }

    public function checkIsFileExist(string $fileUrl): bool
    {
        $filePath = $this->path . $this->getFileNameFromUrl($fileUrl);

        return file_exists($filePath);
    }

    public function removeFile(string $fileUrl): bool
    {
        $filePath = $this->path . $this->getFileNameFromUrl($fileUrl);

        $this->checkIsFileExist($filePath);

        return unlink($filePath);
    }

    private function getFileNameFromUrl(string $fileUrl): string
    {
        return basename($fileUrl);
    }
}
