<?php

namespace Tests\Unit\Services;

use App\Services\Contract\FIleServiceContract;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileServiceTest extends TestCase
{
    protected FIleServiceContract $service;
    protected string $file = '', $filePath = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(FIleServiceContract::class);
    }
    protected function tearDown(): void
    {
        if($this->file != '' && Storage::has($this->file)) {
            Storage::delete($this->file);
        }
        if($this->filePath != '' && Storage::has($this->filePath)) {
            Storage::deleteDirectory($this->filePath); //why not working?
        }
    }


    public function test_file_upload()
    {
        $this->file = $this->uploadedFile();

        $this->assertTrue(Storage::has($this->file));
        $this->assertEquals(Storage::getVisibility($this->file), 'public');
    }

    public function test_file_upload_with_additional_path()
    {
        $this->filePath = 'test';
        $this->file = $this->uploadedFile(additionalPath: $this->filePath);

        $this->assertTrue(Storage::has($this->file));
        $this->assertStringContainsString('test', $this->file);
        $this->assertEquals(Storage::getVisibility($this->file), 'public');
    }

    public function test_remove_existing_file()
    {
        $filePath = $this->uploadedFile(additionalPath: 'test');

        $this->assertTrue(Storage::has($filePath));
        $this->service->remove($filePath);
        $this->assertFalse(Storage::has($filePath));

    }

    protected function uploadedFile(string $fileName = 'test.png', string $additionalPath = ''): string
    {
        $file = UploadedFile::fake()->image($fileName);

        return $this->service->upload($file, $additionalPath);
    }
}
