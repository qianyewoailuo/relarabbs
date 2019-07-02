<?php

/**
 * route_class
 * 将当前请求的路由名称转换为 CSS 类名称, 以便于定制不同页面的样式
 */
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

/**
 * category_active_class
 * 判断分类选中触发 active
 */
function category_active_class($category_id)
{
    return active_class(if_route('categories.show') && if_route_param('category', $category_id));
}

/**
 * make_excerpt
 * 自动生成话题摘要
 */
function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str_limit($excerpt, $length);
}
