<?php

namespace App\Http\Controllers;

use File;
use Response;

class StorageController extends Controller
{
    public function show($filename)
    {
        $path = storage_path('app/public/uploads/' . $filename);

        if (! File::exists($path)) {
            $path = $this->getPathDefaultImage();
            return;
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header('Content-Type', $type);

        return $response;
    }

    private function getPathDefaultImage()
    {
        return '';
    }
}
