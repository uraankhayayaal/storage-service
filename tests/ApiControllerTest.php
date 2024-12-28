<?php

namespace Tests;

use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApiControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_base_endpoint_returns_a_successful_response()
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
        );
    }

    public function test_check_exists_file_success()
    {
        $storage = Storage::fake('public');

        $existsFileName = 'some.exist';

        $existsFile = File::fake()->create($existsFileName);

        $storage->put($existsFileName, $existsFile);

        $this
            ->json('get', '/api/check?url='.'http://localhost:8001/storage/'.$existsFileName)
            ->seeJsonEquals([
                'data' => [
                    'url' => 'http://localhost:8001/storage/'.$existsFileName,
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
                    'url' => 'http://not.found/file',
                    'isExist' => false
                ],
            ]);
    }

    public function test_upload_success()
    {
        $file = UploadedFile::fake()->create('some.new');

        $this
            ->post('/api/upload', ['file' => $file])
            ->assertResponseOk();
    }

    public function test_miltipleupload_success()
    {
        $file1 = UploadedFile::fake()->create('some1.new1');
        $file2 = UploadedFile::fake()->create('some2.new2');

        $this
            ->post('/api/miltipleupload', ['files' => [$file1, $file2]])
            ->assertResponseOk();
    }

    public function test_remove_success()
    {
        $storage = Storage::fake('public');

        $deletingFileName = 'some.deleting';

        $deletingFile = File::fake()->createWithContent($deletingFileName, 'asdasdas');

        $storage->put($deletingFileName, $deletingFile);

        $this
            ->delete('/api/remove?url=' . app('url')->asset("storage/$deletingFileName"))
            ->assertResponseStatus(204);
    }
}
