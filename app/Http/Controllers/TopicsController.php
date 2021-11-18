<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Handlers\ImageUploadHandler;    // 图片处理


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

  // 编辑器-图片上传处理
  public function uploadImage(Request $request, ImageUploadHandler $uploader){
    /*
        服务器端通过返回以下 JSON 来反馈上传状态：
        {
          "success": true/false,
          "msg": "error message", # optional
          "file_path": "[real file path]"
        }

      */

    // 初始化返回数据，默认是失败的
    $data = [
      'success'   => false,
      'msg'       => '上传失败!',
      'file_path' => ''
    ];

    // 判断是否有上传文件，并赋值给 $file
    if ($file = $request->upload_file) {

      /*
        保存图片到本地
        save-引用自定义的图片处理的方法。参数1是图片文件，参数2是存储目录名，参数3是用户id，参数4为图片最大尺寸
      */

      
      $result = $uploader->save($file, 'topics', Auth::id(), 1024);


      // 图片保存成功的话
      if ($result) {
        $data['file_path'] = $result['path'];
        $data['msg']       = "上传成功!";
        $data['success']   = true;
      }
    }

    return $data;

  }

}
