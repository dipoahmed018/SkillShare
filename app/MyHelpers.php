<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

if (!function_exists('assetToPath')) {
    function assetToPath(string $link, string $from)
    {
        $path = strstr($link, $from);
        return $path;
    }
}

if (!function_exists('blobConvert')) {
    function blobConvert($file)
    {
        $data = explode(';base64,', $file);
        if (!is_array($data) || !isset($data[1])) {
            return false;
        };
        $data = base64_decode($data[1]);
        if (!$data) {
            return false;
        }
        return $data;
    }
}

if (!function_exists('chunkUpload')) {
    function chunkUpload($directory, $data, $file_directory = null)
    {
        $files = collect(Storage::disk('temp')->files($directory));
        $response = (object) ['status' => false, 'message' => '', 'file_name' => ''];
        $size = 0;
        if (request()->header('x-cancel') === true) {
            Storage::disk('temp')->deleteDirectory($directory);
            $response->status = 200;
            $response->message = 'File upload has been canceled';
            return $response;
        }

        if ($files->count() > 1) {
            foreach ($files as $key => $filepath) {
                $file_size = (int)File::size(storage_path('app/temp/' . $filepath));
                $size += $file_size;
            }
            if ($size > 1024 * 1024 * 500) {
                Storage::disk('temp')->deleteDirectory($directory);
                $response->status = 422;
                $response->message = 'too large';
            }
            return $response;
        }
        if (request('x-resumeable') == true) {
            if ($size !== 0) {
                $response->status = 200;
                $response->file_name = 'chunk-'. ($files->count() + 0);
                $response->message = 'file upload can be resumed';
                return $response;
            } else {
                $response->status = 404;
                $response->message = 'file not found';
            }
        }



        //chunk and file upload
        $temp_name = 'chunk-' . (string)($files->count() + 1) . '.tmp';
        $file_name = Str::random(20) . '.mp4';
        if (request()->header('x-last') == true && $file_directory) {
            if (!Storage::disk('temp')->exists($directory)) {
                Storage::put($file_directory . $file_name, $data);
                $response->status = 200;
                $response->file_name = $file_name;
                $response->message = 'file complete';
                return $response;
            };
            foreach ($files as $key => $file_path) {
                $content = Storage::disk('temp')->get($file_path);

                Storage::append($file_directory. $file_name, $content, null);
            }
            Storage::append($file_directory . $file_name, $data, null);
            Storage::disk('temp')->deleteDirectory($directory);
            $response->status = 200;
            $response->file_name = $file_name;
            $response->message = 'file complete';
            return $response;
        }
        if (!Storage::disk('temp')->exists($directory)) {
            Storage::disk('temp')->makeDirectory($directory);
        }

        Storage::put('/temp//' . $directory . '/' . $temp_name, $data);
        $response->status = 200;
        $response->file_name = $temp_name;
        $response->message = 'chunk complete';

        return $response;
    }
}
