<?php

declare(strict_types=1);

namespace App\Storages;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

final class S3Storage implements StorageInterface
{
    private S3Client $s3;
    private string $backet = 'public';

    public function __construct(
    ) {
        $this->s3 = app('aws')->createClient('s3');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function putFile(UploadedFile $file, string $fileName): ?string
    {
        try {
            /** @var \Aws\Result $result */
            $result = $this->s3->putObject([
                'Bucket' => $this->backet,
                'Key' => $fileName,
                'SourceFile' => $file->getPathname(),
            ]);
        } catch (S3Exception $exception) {
            Log::error($exception->getMessage());
            return null;
        }

        return $result->get('ObjectURL');
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
        try {
            $this->s3->getObject([
                'Bucket' => $this->backet,
                'Key' => $this->getKeyByUrl($fileUrl),
            ]);
        } catch (S3Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }

        return true;
    }

    public function removeFile(string $fileUrl): bool
    {
        try {
            $this->s3->deleteObject([
                'Bucket' => $this->backet,
                'Key' => $this->getKeyByUrl($fileUrl),
            ]);
        } catch (S3Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }

        return true;
    }

    private function getKeyByUrl(string $fileUrl): string
    {
        return basename($fileUrl);
    }
}
