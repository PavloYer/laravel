<?php

namespace App\Models;

use App\Services\Contract\FIleServiceContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'imageable_id', 'imageable_type'];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function setPathAttribute($path)
    {
        $this->attributes['path'] = app(FIleServiceContract::class)->upload(
            $path['image'],
            $path['directory'] ?? null
        );
    }

    public function url(): Attribute
    {
        return Attribute::get(function ()
        {
            return Storage::url($this->attributes['path']);
        });
    }
}
