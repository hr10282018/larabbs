<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [             //事件监听器
        Registered::class => [        
            SendEmailVerificationNotification::class,
        ],

        //监听Verified，用户设置邮箱已认证触发
        \Illuminate\Auth\Events\Verified::class => [
          \App\Listeners\EmailVerified::class,
      ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
