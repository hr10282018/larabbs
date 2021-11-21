<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');  // 要求必须登录以后才能访问控制器里的所有方法
  }

  public function index()
  {
    // 获取登录用户的所有通知
    /*
      notifications()-是Use模型中的trait-Notifiable，该trait又引用了trait-HasDatabaseNotifications,
      可以发现定义的notifications方法，是取出notifications表中notifiable(notifiable_type)字段值，按照创建时间倒序
    */
    $notifications = Auth::user()->notifications()->paginate(20);
    //dd($notifications);

    // 标记为已读，未读数量清零（此方法在User模型中定义）
    Auth::user()->markAsRead();

    return view('notifications.index', compact('notifications'));
  }

}
