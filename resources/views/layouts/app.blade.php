<!DOCTYPE html>
<!--lang-声明当前页面语言类型 -->
<html lang="{{ app()->getLocale() }}">  <!-- 获取config/app.php中的locale选项,值为zh-CN，(所以要到resources/lang文件夹,定义zh-CN.json文件，定义对应中文以便其他页面使用) -->

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}"> <!--csrf-token 标签是为了方便前端的JavaScript脚本获取CSRF令牌。 -->
  <title>@yield('title', 'LaraBBS') - Laravel 进阶教程</title>
 <!-- Styles -->
 <link href="{{ mix('css/app.css') }}" rel="stylesheet">

 @yield('styles')   <!-- 文字编辑器样式 -->

</head>
<body>
  <div id="app" class="{{ route_class() }}-page"> <!-- route_class()是我们自定义的辅助方法(对应app\helpers.php文件的route_class()方法) -->

    @include('layouts._header')

    <div class="container">

      @include('shared._messages')

      @yield('content')

    </div>

    @include('layouts._footer')
  </div>

  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}"></script>

  @yield('scripts')     <!-- 文字编辑器js -->

</body>

</html>
