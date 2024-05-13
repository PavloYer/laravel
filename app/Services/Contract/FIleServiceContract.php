<?php

namespace App\Services\Contract;

use Illuminate\Http\UploadedFile;

interface FIleServiceContract
{
    public function upload(UploadedFile|string $file, $additionalPath = ''): string;

    public function remove(string $filePath): void;
}
