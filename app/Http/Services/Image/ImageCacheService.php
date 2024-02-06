<?php

namespace App\Http\Services\Image;

use Illuminate\Support\Facades\Config;
use Intervention\Image\Facades\Image;

class ImageCacheService
{

    public function cache(string $imagePath, string $size = '')
    {
        $imageSize = Config::get('image.cache-image-sizes');

        if (!isset($imageSize[$imageSize])) {
            $size = Config::get('image.default-current-cache-image');
        }
        $width = $imageSize[$size]['width'];
        $height = $imageSize[$size]['height'];

        if (file_exists($imagePath)) {
            $image = Image::cache(function ($image) use ($imagePath, $width, $height) {
                return $image->make($imagePath)->fit($width, $height);
            }, Config::get('image.image_cache_life_time'), true);
            return $image->response();
        }
        $image = Image::canvas($width, $height, '#cdcdcd')
            ->text('image not found - 404', $width / 2, $height / 2, function ($font) {
                $font->color('#333333');
                $font->align('center');
                $font->valign('center');
                $font->file(public_path('admin-assets/fonts/Vazirmatn/Vazirmatn-Bold.ttf'));
                $font->size(24);
            });
        return $image->response();
    }
}
