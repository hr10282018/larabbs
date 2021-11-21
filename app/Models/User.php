<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract; //此接口，定义了邮件相关的四个抽象方法
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;    // 消息通知(notify、notifications)
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;  //加载此trait，可使用四个方法（如下所示）
//hasVerifiedEmail() 检测用户 Email 是否已认证；
//markEmailAsVerified() 将用户标示为已认证；
//sendEmailVerificationNotification() 发送Email认证的消息通知，触发邮件的发送；
//getEmailForVerification() 获取发送邮件地址，提供这个接口允许你自定义邮箱字段。

class User extends Authenticatable implements MustVerifyEmailContract   //继承此接口，定义了邮件相关的四个方法
{

    //use Notifiable, MustVerifyEmailTrait;
    use MustVerifyEmailTrait;

    use Notifiable {
        notify as protected laravelNotify;      // 先修改方法名，方便重写，不然会冲突
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [       //允许修改的字段
        'name', 'email', 'password','avatar','introduction'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //用户和话题的关联是一对多
    public function topics(){
      return $this->hasMany(Topic::class);// 可使用$user->topics来获取到用户发布的所有话题数据。
    }
    // 一个用户可以有多条评论
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    //用户策略-当前用户id是否等于关联模型的用户id（只能编辑或删除自己的帖子）
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }


    // 重写notify。该方法在trait-Notifiable的trait-RoutesNotifications中。
    public function notify($instance)   // 此方法接收一个通知实例做参数，通知类的定义在App\Notifications\TopicReplied.php
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()) {
            return;
        }

        // 现在只需要数据库类型通知才需提醒（直接发送Email或者其他的都Pass）
        if (method_exists($instance, 'toDatabase')) {   // toDatabase()-app/Notifications/TopicReplied.php中定义的方法，定义存储到通知表的数据
            $this->increment('notification_count');     // 每当你调用$user->notify()时， users表里的notification_count字段(未读消息数量)将自动+1。
        }

        $this->laravelNotify($instance);
    }

    // 当用户访问通知列表，消除所有未读消息
    public function markAsRead()
    {
        $this->notification_count = 0;  // 用户表未读消息数量设为0
        $this->save();

        /*
          unreadNotifications()-此方法可获取用户read_at字段为null(未读消息)的所有数据。(此方法在trait-Notifiable中的trait-HasDatabaseNotifications文件)
          markAsRead()-作用将未读消息的全部清空，也就是更新通知表中的read_at字段(插入当前时间)，表示已读 。(此方法在trait-Notifiable中的trait-HasDatabaseNotifications中的DatabaseNotification类中定义)
        */
        $this->unreadNotifications->markAsRead();
    }

}
