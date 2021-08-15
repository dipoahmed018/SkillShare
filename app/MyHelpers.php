<?php

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
        if (request()->header('x-cancel')) {
            Log::channel('event')->info($directory);
            Storage::disk('temp')->deleteDirectory($directory);
            $response->status = 200;
            $response->message = 'File upload has been canceled';
            return $response;
        }

        if ($files->count() > 0) {
            foreach ($files as $key => $filepath) {
                $file_size = (int)File::size(storage_path('app/temp/' . $filepath));
                $size += $file_size;
            }
            if ($size > 1024 * 1024 * 500) {
                Storage::disk('temp')->deleteDirectory($directory);
                $response->status = 422;
                $response->message = 'too large';
                return $response;
            }
        }
        if (request()->header('x-resumeable')) {
            if ($size !== 0) {
                $response->status = 200;
                $response->file_name = 'chunk-' . ($files->count() + 1);
                $response->message = 'file upload can be resumed';
                return $response;
            } else {
                $response->status = 404;
                $response->message = 'file not found';
                return $response;
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

                Storage::append($file_directory . $file_name, $content, null);
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

if (!function_exists('saveImage')) {
    function saveImage($file, $url)
    {
        if ($file->getSize() / 1000 > 2000) {
            return response()->json(['error' => ['message' => 'The image uploaded was too big. Image Must be under 2000kb']], 422);
        }
        if (!preg_match('#image/(png|jpg|jpeg)$#', $file->getMimeType())) {
            return response()->json(['error' => ['message' => 'The provided file must be an image of jpg, jpeg or png type']], 422);
        }
        $extension = $file->getClientOriginalExtension();
        $random_name = uniqid() . '.' . $extension;
        $file->storeAs('temp/', $random_name);
        return response()->json(['url' => $url . $random_name ?? 'https://skillshare.com/temp/' . $random_name]);
    }
}

if (!function_exists('deleteFilesBut')) {
    function deleteFileBut($directory, $files)
    {
        Log::channel('event')->info('i was here');
        $destination = collect(Storage::files($directory));
        foreach ($destination as $key => $exist_path) {
            $exists = false;
            foreach ($files as $key => $name) {
                if ($exist_path == $directory . '/' . $name) {
                    $exists = true;
                    break;
                } else {
                    $exists = false;
                }
            }
            if (!$exists) {
                Storage::delete($exist_path);
            }
        }
    }
}

if (function_exists('update_files')) {
    function update_files($files){

    }
}
