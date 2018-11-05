<?php
namespace models;

use PDO;

class Login
{
    // 保护 PDO 对象
    public $pdo;

    public function __construct()
    {
        // 取日志的数据
        $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $this->pdo->exec('SET NAMES utf8');
    }

    public function register($username,$password)
    {
        $stmt = $this->pdo->prepare("INSERT INTO admin (username,password) VALUES(?,?)");
        return $stmt->execute([
                                $username,
                                $password,
                            ]);
    }

}