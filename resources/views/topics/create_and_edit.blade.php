@extends('layouts.app')

@section('content')

<!-- 创建、修改话题页面 -->
  <div class="container">
    <div class="col-md-10 offset-md-1">
      <div class="card ">

        <div class="card-body">
          <h2 class="">
            <i class="far fa-edit"></i>
            @if($topic->id)
            编辑话题
            @else
            新建话题
            @endif
          </h2>

          <hr>

          @if($topic->id)   <!-- 如果存在，则是修改。否则是创建 -->
            <form action="{{ route('topics.update', $topic->id) }}" method="POST" accept-charset="UTF-8">
              <input type="hidden" name="_method" value="PUT">
          @else
            <form action="{{ route('topics.store') }}" method="POST" accept-charset="UTF-8">
          @endif

              <input type="hidden" name="_token" value="{{ csrf_token() }}">

              @include('shared._error')

              <div class="form-group">
                <input class="form-control" type="text" name="title" value="{{ old('title', $topic->title ) }}" placeholder="请填写标题" required />
              </div>

              <div class="form-group">
                <select class="form-control" name="category_id" required>
                  <option value="" hidden disabled selected>请选择分类</option>
                  @foreach ($categories as $value)
                  <option value="{{ $value->id }}">{{ $value->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <textarea name="body" class="form-control" id="editor" rows="6" placeholder="请填入至少三个字符的内容。" required>{{ old('body', $topic->body ) }}</textarea>
              </div>

              <div class="well well-sm">
                <button type="submit" class="btn btn-primary"><i class="far fa-save mr-2" aria-hidden="true"></i> 保存</button>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>

@endsection


<!-- 继承app视图的文本编辑器css和js -->
@section('styles')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
@stop

@section('scripts')
  <script type="text/javascript" src="{{ asset('js/module.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/hotkeys.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/uploader.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/simditor.js') }}"></script>

  <script>
    $(document).ready(function() {
      var editor = new Simditor({
        textarea: $('#editor'),
        // 文本编辑器-图片上传
        upload: {
          url: '{{ route('topics.upload_image') }}',    // 处理上传图片的 URL
          params: {
            _token: '{{ csrf_token() }}'  // 表单提交的参数，Laravel 的POST请求必须带防止CSRF跨站请求伪造的_token 参数
          },
          fileKey: 'upload_file', // 是服务器端获取图片的键值，我们设置为 upload_file
          connectionCount: 3,     // 最多只能同时上传 3 张图片
          leaveConfirm: '文件上传中，关闭此页面将取消上传。'    // 上传过程中，用户关闭页面时的提醒
        },
        pasteImage: true,   // 设定是否支持图片黏贴上传，这里我们使用 true 进行开启

      });
    });
  </script>
@stop
