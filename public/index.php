<?php
session_start();
// 加载视图
function view($file, $data=[])
{
    extract($data);
    include(ROOT.'views/'.$file .'.html');
}

define('ROOT',dirname('__FILE__').'/../');  // 定义一个根目录
require(ROOT."vendor/autoload.php");
// 类的自动加载
function load($class)
{
    $stmt = str_replace('\\','/',$class);
    require(ROOT.$stmt.'.php');
}
// 加载函数
spl_autoload_register('load');

// 解析路由
// $controller = '\controllers\IndexController';
// $action = 'index';
if(isset($_SERVER['PATH_INFO']))
{
    $router = explode('/',$_SERVER['PATH_INFO']);
    $controller = ucfirst($router[1]).'Controller';
    $action = $router[2];
}else{
    $controller = "IndexController";
    $action = "index";
}

$Controller = "controllers\\".$controller;
$c = new $Controller;
$c->$action();

function redirect($url)
{
    header("Location:".$url);
    die;
}

// 获取当前 URL 上所有的参数，并且还能排除掉某些参数
// 参数：要排除的变量
function getUrlParams($except = [])
{
    // 循环删除变量
    foreach($except as $v)
    {
        unset($_GET[$v]);
    }


    $str = '';
    foreach($_GET as $k => $v)
    {
        $str .= "$k=$v&";
    }

    return $str;
}