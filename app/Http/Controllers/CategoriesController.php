<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use App\Models\Link;

class CategoriesController extends Controller
{
    //
    public function show(Category $category,Topic $topic,Request $request,User $user,Link $link){

      //读取分类ID关联的话题，每页20条
      $topics =$topic->withOrder($request->order)   // withOrder-使用Topic模型定义的方法
                      ->where('category_id',$category->id)
                      ->with('user', 'category')   // 预加载防止 N+1 问题
                      ->paginate(20);

      // 获取活跃用户列表
      $active_users = $user->getActiveUsers();

      // 推荐资源链接
      $links = $link->getAllCached();

      //传参变量话题和分类到模板
      return view('topics.index',compact('topics', 'category','active_users','links'));

    }
}
