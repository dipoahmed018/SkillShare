<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\FileLink;
use App\Models\Post;
use App\Observers\Commentobserver;
use App\Observers\FileObserver;
use App\Observers\Postobserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        FileLink::observe(FileObserver::class);
        Post::observe(Postobserver::class);
        Comment::observe(Commentobserver::class);
    }
}
