<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]); // 限制未登录用户（发帖）
    }

	public function index(Request $request, Topic $topic)
	{
    //可以$topic->withOrder 或 Topic::withOrder
  // $request->order 是获取http://larabbs.test/topics?order=recent中的 order 参数。
		$topics = $topic->withOrder($request->order) // withOrder-使用Topic模型定义的方法
                    ->with('user','category')
                    ->paginate(20); //预加载所用的关联属性
		return view('topics.index', compact('topics'));

	}

    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }

  // 创建话题分类
	public function create(Topic $topic)
	{
    $categories = Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

  //编辑话题保存
	public function store(TopicRequest $request,Topic $topic)
	{
    /*
      获取用户请求的所有数据组成的数组，如 ['title' => '标题',...]
      fill 方法会将传参的键值数组填充到模型的属性中，如以上数组，$topic->title 的值为 标题
    */
    $topic->fill($request->all());

    $topic->user_id = Auth::id();   // 获取当前登录的ID
    $topic->save();     // 保存到数据库中
		//$topic = Topic::create($request->all());
		return redirect()->route('topics.show', $topic->id)->with('success', '帖子创建成功！');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
		return view('topics.create_and_edit', compact('topic'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->route('topics.show', $topic->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', 'Deleted successfully.');
	}


}
