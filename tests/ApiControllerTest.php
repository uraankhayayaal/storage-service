<?php

namespace Tests;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApiControllerTest extends TestCase
{
    private Filesystem $storage;

    protected function setUp(): void
    {
        if (! $this->app) {
            $this->refreshApplication();
        }

        $this->setUpTraits();

        $this->storage = Storage::fake(
            env('APP_STORAGE', 'disk') === 'cloud'
                ? 's3'
                : 'local'
        );
    }

    public function test_check_exists_file_success()
    {
        $existsFileName = 'some.exist';

        $existsFile = File::fake()->create($existsFileName);

        $this->storage->put($existsFileName, $existsFile);

        $this
            ->json('get', '/api/check?url='.'http://localhost:8001/storage/'.$existsFileName)
            ->seeJsonEquals([
                'data' => [
                    'isExist' => true
                ],
            ]);
    }

    public function test_check_not_exists_file_success()
    {
        $this
            ->json('get', '/api/check?url='.'http://not.found/file')
            ->seeJsonEquals([
                'data' => [
                    'isExist' => false
                ],
            ]);
    }

    public function test_upload_success()
    {
        $file = UploadedFile::fake()->create('some.new');

        $this
            ->call('post', '/api/upload', [], [], ['file' => $file])
            ->assertCreated();
    }

    public function test_miltipleupload_success()
    {
        $file1 = UploadedFile::fake()->create('some1.new1');
        $file2 = UploadedFile::fake()->create('some2.new2');

        $this
            ->call('post', '/api/miltipleupload', [], [], ['files' => [$file1, $file2]])
            ->assertCreated();
    }

    public function test_remove_success()
    {
        $deletingFileName = 'some.deleting';

        $deletingFile = File::fake()->createWithContent($deletingFileName, 'asdasdas');

        $this->storage->put($deletingFileName, $deletingFile);

        $this
            ->delete('/api/remove?url=' . app('url')->asset("storage/$deletingFileName"))
            ->assertResponseStatus(204);
    }
}
