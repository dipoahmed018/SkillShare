<?php

use App\Models\FileLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


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

//save image in temp directory
if (!function_exists('saveImage')) {
    function saveImage($file, $url, $directory = 'temp/')
    {
        if ($file->getSize() / 1000 > 2000) {
            return response()->json(['error' => ['message' => 'The image uploaded was too big. Image Must be under 2000kb']], 422);
        }
        if (!preg_match('#image/(png|jpg|jpeg)$#', $file->getMimeType())) {
            return response()->json(['error' => ['message' => 'The provided file must be an image of jpg, jpeg or png type']], 422);
        }
        $extension = $file->getClientOriginalExtension();
        $random_name = uniqid() . ".$extension";
        $file->storeAs($directory, $random_name);
        return response()->json(['url' =>  "$url?name=$random_name"], 200);
    }
}
if (!function_exists("getImage")) {
    function getImage($name)
    {
        $file_details = FileLink::where('file_name', $name)->first();
        if ($file_details) {
            $owner_table = $file_details->owner;
            if (request()->user()->cannot('access', $owner_table)) {
                return abort(403, 'you are not allowed to access this file');
            }
            $path = Storage::path($file_details->file_link);
            return response()->file($path);
        } else if (Storage::exists("temp/$name")) {
            return response()->file(Storage::path("temp/$name"));
        }
        return response('your requested file does not exists', 404);
    }
}

if (!function_exists('update_files')) {
    function update_files($new, $old, $destination)
    {
        $delete = $new->diff($old);
        $add = $old->diff($new);
        $delete->each(fn ($name) => Storage::delete($destination . $name));
        $add->each(function ($name) use ($destination) {
            if (Storage::exists("temp/$name")) {
                $image = Image::make(Storage::path($destination . $name));
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(Storage::path("temp/$name"), 80);
                Storage::delete("temp/$name");
            }
        });
    }
}
