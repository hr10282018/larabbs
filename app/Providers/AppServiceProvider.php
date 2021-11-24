<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{


  public function boot()
	{
		\App\Models\User::observe(\App\Observers\UserObserver::class);
		\App\Models\Reply::observe(\App\Observers\ReplyObserver::class);
		\App\Models\Topic::observe(\App\Observers\TopicObserver::class);  //注册Topic模型观察器
    \App\Models\Link::observe(\App\Observers\LinkObserver::class);  // 注册Link模型监控器

  }

  // 只在开发环境中加载此扩展包
  public function register()
  {
    if (app()->isLocal()) {
      $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
    }
  }


}
