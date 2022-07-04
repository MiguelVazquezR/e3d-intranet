<?php

namespace App\ServiceClasses;

use Intervention\Image\Facades\Image;

class ImageHandler
{

    /**
     * Reduces image's weight and create a 600x360 standar size for all images.
     *
     * @param  mix  $image
     * @return string
     */
    public static function prepareImage($image, $folder)
    {
        $image_name = uniqid("", true) . '.' . $image->extension();
        $image_path = storage_path() . "/app/public/$folder/$image_name";
        Image::make($image)
            ->resize(null, 360, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->resizeCanvas(600, 360, 'center', false, 'FFFFFF')
            ->save($image_path, 40);

        return $image_name;
    }

    /**
     * Reduces image's weight.
     *
     * @param  mix  $image
     * @return string
     */
    public static function optimize($image, $folder)
    {
        $image_name = uniqid("", true) . '.' . $image->extension();
        $image_path = storage_path() . "/app/public/$folder/$image_name";
        Image::make($image)
            ->save($image_path, 40);
    }
}
