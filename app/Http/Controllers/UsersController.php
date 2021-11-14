<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;  //图片上传

class UsersController extends Controller
{

    public function __construct()
    {
      //黑名单过滤，除了show，其他动作都必须用户登录才能访问
        $this->middleware('auth', ['except' => ['show']]);
    }

    //个人页面展示
    public function show(User $user){
      return view('users.show',compact('user'));
    }

    //个人页面编辑
    public function edit(User $user)
    {
      $this->authorize('update', $user);
      return view('users.edit', compact('user'));
    }

    //保存修改用户信息
    public function update(UserRequest $request,ImageUploadHandler $uploader,User $user){

      $this->authorize('update', $user);//授权认证(用户编辑自己的资料)

      //dd($request->avatar);   //调试文件图片上传
      $data = $request->all();

      if ($request->avatar) {   //判断是否有图片上传
        $result = $uploader->save($request->avatar, 'avatars', $user->id,416);  //调用保存文件的方法
        if ($result) {    //如果保存成功
            $data['avatar'] = $result['path'];
        }
      }
      $user->update($data);

      return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }
}

