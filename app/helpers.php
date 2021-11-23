<?php
use Illuminate\Support\Str;

//将当前请求的路由名称转换为 CSS 类名称,作用是允许我们针对某个页面做页面样式定制
function route_class()
{
    //return Route::currentRouteName();
    return str_replace('.', '-', Route::currentRouteName());  //返回当前路由的名称(替换'.'符号,是针对x.y这类路由)

    /*

    路由名称 categories.show，对应 CSS类名为 categories-show-page

    */
}

//active扩展包-识别选中状态，增加active
function category_nav_active($category_id)
{
  /*
    if_route () - 判断当前对应的路由是否是指定的路由；
    if_route_param () - 判断当前的 url 有无指定的路由参数。
  */
  return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}

//生成摘录方法
function make_excerpt($value, $length = 200)
{
  /*
  strip_tags-去除字符串中的 HTML、XML 以及 PHP 的标签。
  preg_replace-搜索参数3 匹配 参数1的部分，用参数2替换
  trim-移除字符串两侧的空白字符或其他预定义字符（参数2）。
  */
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return Str::limit($excerpt, $length);
}


// 后台config\administrator\topics中使用，
function model_admin_link($title, $model)
{
    return model_link($title, $model, 'admin');
}
function model_link($title, $model, $prefix = '')
{
    // 获取数据模型的复数蛇形命名
    $model_name = model_plural_name($model);

    // 初始化前缀
    $prefix = $prefix ? "/$prefix/" : '/';

    // 使用站点 URL 拼接全量 URL
    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

    // 拼接 HTML A 标签，并返回
    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}
function model_plural_name($model)
{
    // 从实体中获取完整类名，例如：App\Models\User
    $full_class_name = get_class($model);

    // 获取基础类名，例如：传参 `App\Models\User` 会得到 `User`
    $class_name = class_basename($full_class_name);

    // 蛇形命名，例如：传参 `User`  会得到 `user`, `FooBar` 会得到 `foo_bar`
    $snake_case_name = Str::snake($class_name);

    // 获取子串的复数形式，例如：传参 `user` 会得到 `users`
    return Str::plural($snake_case_name);
}
