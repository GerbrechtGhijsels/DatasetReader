<?php

namespace App\Helpers;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class FileHelper
{
    /**
     * Uploads a file to the local storage under /files
     *
     * @param $uploadedFile \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|array|null  file that needs to be uploaded
     * @param $filename string Filename for the file
     * @return string filename
     */
    static function uploadFile($uploadedFile)
    {
        $filename = time().$uploadedFile->getClientOriginalName();

        Storage::disk('local')->putFileAs(
            'files/',
            $uploadedFile,
            $filename
        );
        return $filename;
    }

    /**
     * Uploads a file to the local storage under /files
     * Only works locally!
     *
     * @param $filepath string Filename for the file
     * @return string filename
     */
    static function uploadFileWithPath($filepath)
    {
        $filename = time().basename($filepath);
        Storage::disk('local')->putFileAs(
            'files/',
            new File($filepath),
            $filename
        );
        return $filename;
    }

    /**
     * Delete file from the local storage under /files
     *
     * @param $filepath string filepath to file
     */
    static function deleteFile($filepath)
    {
        Storage::delete($filepath);
    }
}