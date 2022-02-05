<?php

namespace App\Utils;

class ImageUploader
{
    /**
     * @param string $folder
     * @param $file
     * @return string
     */
    public function upload(string $folder, $file): string
    {
        $imagePath = $file->storeAs(
            $folder,
            date('YmdHis') . '.' . $file->extension(),
            'public'
        );

        return $imagePath;
    }
}
