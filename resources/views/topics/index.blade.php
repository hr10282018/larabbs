@extends('layouts.app')

@section('title',  isset($category) ? $category->name : '话题列表') <!-- 三元运算符 -->

@section('content')

<div class="row mb-5">
  <div class="col-lg-9 col-md-9 topic-list">

  <!-- 显示分类信息(分类名和描述)  -->
    @if (isset($category))
      <div class="alert alert-info" role="alert">
        {{ $category->name }} ：{{ $category->description }}
      </div>
    @endif

    <div class="card ">
      <div class="card-header bg-transparent">
        <ul class="nav nav-pills">
          <li class="nav-item">
            <!-- active_class 是acitve扩展包的方法，参数1-判断是否满足传入的指定条件（条件：如果当前get请求uri中的order参数不等于recent，满足返回active）-->
            <!-- if_query-判断指定的GET变量是否符合设置的值(判断该url的order参数值等于recent) -->
            <!-- Request::url() 获取的是当前请求的 URL -->
            <a class="nav-link {{ active_class( ! if_query('order', 'recent')) }}" href="{{ Request::url() }}?order=default">
              最后回复
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ active_class(if_query('order', 'recent')) }}" href="{{ Request::url() }}?order=recent">
              最新发布
            </a>
          </li>
        </ul>
      </div>

      <div class="card-body">
        <!-- 话题列表 -->
        @include('topics._topic_list', ['topics' => $topics])   <!-- 为该视图绑定数据topics以便使用 -->

        <!-- 分页 -->
        <div class="mt-5">
          {!! $topics->appends(Request::except('page'))->render() !!}
        </div>

      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-3 sidebar">
    @include('topics._sidebar')
  </div>
</div>

@endsection
