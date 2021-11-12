<?php


//将当前请求的路由名称转换为 CSS 类名称,作用是允许我们针对某个页面做页面样式定制
function route_class()
{
    //return Route::currentRouteName();
    return str_replace('.', '-', Route::currentRouteName());  //返回当前路由的名称(替换'.'符号,是针对x.y这类路由)
}
