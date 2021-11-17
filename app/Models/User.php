<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract; //此接口，定义了邮件相关的四个抽象方法
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;  //加载此trait，可使用四个方法（如下所示）
//hasVerifiedEmail() 检测用户 Email 是否已认证；
//markEmailAsVerified() 将用户标示为已认证；
//sendEmailVerificationNotification() 发送Email认证的消息通知，触发邮件的发送；
//getEmailForVerification() 获取发送邮件地址，提供这个接口允许你自定义邮箱字段。

class User extends Authenticatable implements MustVerifyEmailContract   //继承此接口，定义了邮件相关的四个方法
{

    use Notifiable, MustVerifyEmailTrait;
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
}
