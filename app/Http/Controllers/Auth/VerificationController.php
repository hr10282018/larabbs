<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');  //设置所有控制器动作需要登录后才能访问
        $this->middleware('signed')->only('verify');  //设定了只有 verify动作使用signed中间件进行认证,signed中间件是一种由框架提供的很方便的URL签名认证方式
        $this->middleware('throttle:6,1')->only('verify', 'resend');  //throttle-访问评率限制，1分钟不能超6次
      
    }
}
