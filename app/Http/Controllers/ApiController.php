<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetFileQuery;
use App\Http\Requests\RemoveFileQuery;
use App\Http\Requests\UploadFileForm;
use App\Http\Requests\UploadFilesForm;
use App\Http\Responses\FileResponse;
use App\Http\Responses\UploadFileSuccessResponse;
use App\Http\Responses\UploadFilesSuccessResponse;
use App\Services\UploaderService;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    public function __construct(
        private UploaderService $service,
    ) {}

    public function check(GetFileQuery $getFileQuery): FileResponse
    {
        $isExist = $this->service->checkIsFileExist($getFileQuery->url);

        return new FileResponse(isExist: $isExist);
    }

    public function upload(UploadFileForm $uploadFileForm): UploadFileSuccessResponse
    {
        $fileUrl = $this->service->upload($uploadFileForm);

        return new UploadFileSuccessResponse(url: $fileUrl);
    }

    public function miltipleupload(UploadFilesForm $uploadFilesForm): UploadFilesSuccessResponse
    {
        $fileUrls = $this->service->miltipleupload($uploadFilesForm);

        return new UploadFilesSuccessResponse(urls: $fileUrls);
    }

    public function remove(RemoveFileQuery $removeFileQuery): JsonResponse
    {
        $this->service->remove($removeFileQuery);

        return response()->json([], 204);
    }
}
