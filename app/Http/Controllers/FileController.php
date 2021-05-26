<?php

namespace App\Http\Controllers;


use App\Helpers\FileHelper;
use App\Jobs\ProcessJsonJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    public function upload(Request $request)
    {
        $uploadedFile = $request->file('file');

        $filename = FileHelper::uploadFile($uploadedFile);

        $filters = [ 'date_of_birth' => 'nullable|olderThan:18|youngerThan:65'];
        ProcessJsonJob::dispatch(Storage::path('files/'.$filename),$filters);

        return response()->json(['success' => 'json']);

    }
}