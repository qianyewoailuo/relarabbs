<?php

/**
 * route_class
 * 将当前请求的路由名称转换为 CSS 类名称, 以便于定制不同页面的样式
 */
function route_class()
{
    return str_replace('.','-',Route::currentRouteName());
}
