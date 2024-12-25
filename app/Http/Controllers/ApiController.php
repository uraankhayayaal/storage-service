<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetFileQuery;
use App\Http\Requests\RemoveFileQuery;
use App\Http\Requests\UploadFileForm;
use App\Http\Requests\UploadFilesForm;
use App\Http\Responses\FileResponse;
use App\Http\Responses\RemoveFileErrorResponse;
use App\Http\Responses\RemoveFileSuccessResponse;
use App\Http\Responses\UploadFileSuccessResponse;
use App\Http\Responses\UploadFileErrorResponse;
use App\Http\Responses\UploadFilesSuccessResponse;
use App\Services\UploaderService;
use Laravel\Lumen\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    public function __construct(
        private UploaderService $service,
    ) {}

    public function check(GetFileQuery $getFileQuery): FileResponse
    {
        $isExist = $this->service->checkIsFileExist($getFileQuery->url);

        return new FileResponse(
            url: $getFileQuery->url,
            isExist: $isExist,
        );
    }

    public function upload(UploadFileForm $uploadFileForm): UploadFileSuccessResponse|UploadFileErrorResponse
    {
        $fileUrl = $this->service->upload($uploadFileForm);

        return $fileUrl !== null
            ? new UploadFileSuccessResponse(url: $fileUrl)
            : new UploadFileErrorResponse();
    }

    public function miltipleupload(UploadFilesForm $uploadFilesForm): UploadFilesSuccessResponse
    {
        $fileUrls = $this->service->miltipleupload($uploadFilesForm);

        return new UploadFilesSuccessResponse(urls: $fileUrls);
    }

    public function remove(RemoveFileQuery $removeFileQuery): RemoveFileSuccessResponse|RemoveFileErrorResponse
    {
        $isDeleted = $this->service->remove($removeFileQuery->url);

        return $isDeleted
            ? new RemoveFileSuccessResponse()
            : new RemoveFileErrorResponse();
    }
}
