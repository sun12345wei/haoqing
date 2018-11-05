<?php
namespace models;

use PDO;

class Admin extends Model
{
    // 设置这个模型对应的表
    protected $table = 'admin';
    // 设置允许接收的字段
    protected $fillable = ['username','password'];
    
    public function insert($username,$password,$role_id)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $sql = "INSERT INTO admin(username,password) VALUE(?,?)";
        $stmt = $pdo->prepare($sql);
        $ret = $stmt->execute([
            $username,
            $password,
        ]);
        $data = $pdo->lastInsertId();
     
        foreach($role_id as $v)
        {
            $sql1 = "INSERT INTO admin_role(role_id,admin_id) VALUE(?,?)";
            $ret = $pdo->prepare($sql1);
            $ret->execute([
                $v,
                $data,
            ]);
        }
    }

    public function findOne($id)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE id=?");
        $stmt->execute([$id]);
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);
        return $ret;
    }

    // 取出拥有的权限ID
    public function getPriIds($adminId)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        // 预处理
        $stmt = $pdo->prepare('SELECT role_id FROM admin_role WHERE admin_id=?');
        // 执行
        $stmt->execute([
            $adminId
        ]);
        $sql =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $ret = [];
        foreach($sql as $v){
             $ret[] = $v['role_id'];
        }
        
        $res = $pdo->query("SELECT * FROM role");
        $data = $res->fetchAll(PDO::FETCH_ASSOC);
        // 取数据
        return [
            'ret'=>$ret,
            'data'=>$data
        ];
    }

    // 修改
    public function update($username,$id,$role_id)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $stmt = $pdo->prepare("UPDATE admin SET username=? WHERE id=?");
        $stmt->execute([
            $username,
            $id,
        ]);
        // 删除原权限
        $stmt = $pdo->prepare("DELETE FROM admin_role WHERE admin_id=?");
        $stmt->execute([
            $id
        ]);

        $stmt = $pdo->prepare("INSERT INTO admin_role(role_id,admin_id) VALUES(?,?)");
        foreach($role_id as $v)
        {
            $stmt->execute([
                $v,
                $id
            ]);
        }
    }

    // 删除
    public function delete($id)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $stmt = $pdo->prepare("DELETE FROM admin WHERE id=?");
        $stmt->execute([
            $id,
        ]);

        $stmt1 = $pdo->prepare("DELETE FROM admin_role WHERE admin_id=?");
        $stmt1->execute([
            $_GET['id'],
        ]);
    }

    

    
}
