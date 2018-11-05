<?php
namespace controllers;

class BaseController
{
    public function __construct()
    {
        // 判断登录
        if(!isset($_SESSION['id']))
        {
            redirect('login/index');
        }


        // var_dump($_SESSION['root']);

        
        if(isset($_SESSION['root'])){
           
            return;
        }

        // 获取将要访问的路径
        $path = isset($_SERVER['PATH_INFO'])? trim($_SERVER['PATH_INFO'], '/') : 'index/index';
      
        // 设置一个白名单
        $whiteList = ['index/index'];
        // 判断是否有权访问

        if(!in_array($path, array_merge($whiteList, $_SESSION['url_path'])))
        {
            die('无权访问！');
        }
    }
}