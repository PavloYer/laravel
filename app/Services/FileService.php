<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService implements Contract\FIleServiceContract
{

    public function upload(string|UploadedFile $file, $additionalPath = ''): string
    {
        if (is_string($file)) {
            return str_replace('public/storage', '', $file);
        }

        $additionalPath = !empty($additionalPath) ? $additionalPath . '/' : '';
        $filePath = $additionalPath . Str::random() . time() . '.' . $file->getClientOriginalExtension();
        Storage::put($filePath, File::get($file));
        Storage::setVisibility($filePath, 'public');

        return $filePath;
    }

    public function remove(string $filePath): void
    {
        Storage::delete($filePath);
    }
}
