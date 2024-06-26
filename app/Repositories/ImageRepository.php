<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;

class ImageRepository implements Contracts\ImageRepositoryContract
{

    public function attach(Model $model, string $relation, array $images = [], ?string $directory = null): void
    {
        if (!method_exists($model, $relation)) {
            throw new Exception($model::class . " doesn't support relation [$relation]");
        }

        if (!empty($images)) {
            foreach ($images as $image) {
                call_user_func([$model, $relation])
                    ->create([
                        'path' => [
                            'image' => $image,
                            'directory' => $directory]
                    ]);
            }
        }
    }
}
