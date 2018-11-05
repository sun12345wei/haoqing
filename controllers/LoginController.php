<?php
namespace controllers;

use models\User;

class LoginController
{
    public function test()
    {
        $model = new User;
        $model->getUalPath(1);
    }
    
    public function register()
    {
        view('login/register');
    }

    public function index()
    {
        view('login/index');
    }

    public function store()
    {
        
        // 1. 接收表单
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        // var_dump($username,$password);
        // die;

        // 2. 插入到数据库中
        $login = new \models\Login;
        $ret = $login->register($username, $password);
        if(!$ret)
        {
            die('注册失败！');
        }
        redirect('/login/index');

        
    }
}