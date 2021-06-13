<?php

namespace App\Observers;

use App\Models\FileLink;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileObserver
{
    /**
     * Handle the FileLink "created" event.
     *
     * @param  \App\Models\FileLink  $fileLink
     * @return void
     */
    public function created(FileLink $fileLink)
    {
        Log::channel('event')->info('observer file',['creating']);
    }

    /**
     * Handle the FileLink "updated" event.
     *
     * @param  \App\Models\FileLink  $fileLink
     * @return void
     */
    public function updated(FileLink $fileLink)
    {
        //
    }

    /**
     * Handle the FileLink "deleted" event.
     *
     * @param  \App\Models\FileLink  $fileLink
     * @return void
     */
    public function deleted(FileLink $fileLink)
    {
        $public = ['thumblin', 'introduction', 'profile_photo', 'profile_video'];
        $private = ['tutorial', 'records', 'post', 'comment', 'message'];
        if (in_array($fileLink->file_type, $public)) {
            // Log::channel('event')->info('file observer public inside', [$fileLink]);
            $path = assetToPath($fileLink->file_link, '/'.$fileLink->fileable_type.'/' . $fileLink->file_type);
            Storage::disk('public')->delete($path);
        }
        if (in_array($fileLink->file_type, $private)) {
            // Log::channel('event')->info('file observer private inside', [$fileLink]);
            Storage::delete($fileLink->file_link);
        }
    }

    /**
     * Handle the FileLink "restored" event.
     *
     * @param  \App\Models\FileLink  $fileLink
     * @return void
     */
    public function restored(FileLink $fileLink)
    {
        //
    }

    /**
     * Handle the FileLink "force deleted" event.
     *
     * @param  \App\Models\FileLink  $fileLink
     * @return void
     */
    public function forceDeleted(FileLink $fileLink)
    {
        Log::channel('event')->info('file observer',['force deleting']);
    }
}
