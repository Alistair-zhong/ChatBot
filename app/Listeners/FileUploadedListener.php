<?php

namespace App\Listeners;


use App\File;
use niro\Uploads\Events\FileUploaded;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FileUploadedListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param niro\Uploads\Events\FileUploaded  $event
     * @return void
     */
    public function handle(FileUploaded $event)
    {
        $event->fileMeta();
        return File::createFromData($event->fileMeta())->id;
    }
}
