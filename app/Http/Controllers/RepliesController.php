<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;

class RepliesController extends Controller
{

  public function __construct()
  {
    //$this->middleware('auth', ['except' => ['index', 'show']]);
    $this->middleware('auth');
  }

  // 保存回复的数据
	public function store(ReplyRequest $request,Reply $reply)
	{
    $reply->content=$request->content;
    $reply->user_id=Auth::id();   // 当前用户id
    $reply->topic_id =$request->topic_id;
    $reply->save();
    // link-带参数的URL
		return redirect()->to($reply->topic->link())->with('success', '评论创建成功^_^');
	}


	public function destroy(Reply $reply)
	{
		$this->authorize('destroy', $reply);    // 授权策略
		$reply->delete();

		return redirect()->to($reply->topic->link())->with('success', '评论删除成功^_^');
	}
}
