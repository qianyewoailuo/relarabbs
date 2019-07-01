<?php

/**
 * route_class
 * 将当前请求的路由名称转换为 CSS 类名称, 以便于定制不同页面的样式
 */
function route_class()
{
    return str_replace('.','-',Route::currentRouteName());
}


function category_active_class($category_id)
{
    return active_class(if_route('categories.show') && if_route_param('category',$category_id));
}
