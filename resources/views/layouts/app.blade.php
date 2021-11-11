<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">  <!-- 获取的是 config/app.php 中的 locale选项,值为zh-CN -->

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}"> <!--csrf-token 标签是为了方便前端的JavaScript脚本获取CSRF令牌。 -->
  <title>@yield('title', 'LaraBBS') - Laravel 进阶教程</title>
 <!-- Styles -->
 <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <div id="app" class="{{ route_class() }}-page"> <!-- route_class()是我们自定义的辅助方法(app\helpers.php) -->

    @include('layouts._header')

    <div class="container">

      @include('shared._messages')

      @yield('content')

    </div>

    @include('layouts._footer')
  </div>

  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
