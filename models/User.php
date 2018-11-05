<?php
namespace Models;

use PDO;

class User
{
    // 保护 PDO 对象
    public $pdo;
    public function __construct()
    {
        // 取日志的数据
        $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $this->pdo->exec('SET NAMES utf8');
    }
    public function index($username, $password)
    {
        // 根据 username 和 password 查询数据库
        $stmt = $this->pdo->prepare('SELECT * FROM admin WHERE username=? AND password=? ');
        // 执行SQL
        $stmt->execute([
            $username,
            $password,
        ]);
        // 取出数据1
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // 是否有这个账号
        if($user)
        {
            // 登录成功，把用户信息保存到 SESSION
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // 查看该管理员是否有一个角色ID=1
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM admin_role WHERE role_id=1 AND admin_id=?');
            $stmt->execute([$_SESSION['id']]);
            $c = $stmt->fetch(PDO::FETCH_COLUMN);
            
            if($c>0){
                $_SESSION['root'] = true;
                
                
            }else{
                // 取出这个管理员有限访问的路
                $_SESSION['url_path'] = $this->getUalPath($_SESSION['id']);
                var_dump($_SESSION['root']);
                // exit;
            }

            return true;
        }
        else
        {
            return FALSE;
        }
    }

    public function logout()
    {
    
       
        // unset($_SESSION);
        $_SESSION = [];
       
        // session_destroy();

        var_dump($_SESSIN['url_path']);

        // die;

        redirect('/login/index');
    }

    // 获取一个管理员有权访问的路径
    public function getUalPath($AdminId)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $sql = "SELECT c.url_path
                    FROM admin_role a 
                    LEFT JOIN role_privlege b ON a.role_id=b.role_id
                    LEFT JOIN privilege c ON b.pri_id=c.id
                    WHERE a.admin_id=? AND c.url_path!=''";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$AdminId]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 把二维数组转成一维数组
        $_ret = [];

        foreach($data as $v)
        {
            // 判断是否有多个URL (包含',')
            // strpos 找出一个字符串中某个字符的位置
            if(FALSE === strpos($v['url_path'], ','))
            {
                // 如果没有','，就直接拿过来
                $_ret[] = $v['url_path'];
            }
            else
            {
                // 如果有','，就转成数组
                $_tt = explode(',', $v['url_path']);
                // 把转完之后的数组合并到一维数组中
                $_ret = array_merge($_ret, $_tt);
            }        
        }
       return $_ret;
    }

}