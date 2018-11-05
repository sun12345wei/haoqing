<?php
namespace models;

use PDO;

class Role extends Model
{
    // 设置这个模型对应的表
    protected $table = 'role';
    // 设置允许接收的字段
    protected $fillable = ['role_name'];

    public function insert($role_name,$pri_id)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $sql = "INSERT INTO role(role_name) VALUE(?)";
        // echo $sql;die;
        $stmt = $pdo->prepare($sql);
        // var_dump($stmt);die;
        $ret = $stmt->execute([
            $role_name,
        ]);
        $data = $pdo->lastInsertId();

        foreach($pri_id as $v){
            $sql1 = "INSERT INTO role_privlege(pri_id,role_id) VALUES(?,?)";
            $res = $pdo->prepare($sql1);
            $res->execute([
                $v,
                $data,
            ]);
        }
        // var_dump($ret);die;
    }

    public function findOne($id)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $stmt = $pdo->prepare("SELECT * FROM role WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch( PDO::FETCH_ASSOC );
    }

    // 取出拥有的权限ID
    public function getPriIds($roleId)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        // 预处理
        $stmt = $pdo->prepare('SELECT pri_id FROM role_privlege WHERE role_id=?');
        // 执行
        $stmt->execute([
            $roleId
        ]);
        // 取数据
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // 转成一维的
        $_ret = [];
        foreach($data as $k => $v)
        {
            $_ret[] = $v['pri_id'];
        }
        // 把一维的返回
        return $_ret;
    }

    // 修改
    public function update($role_name,$pri_id,$id)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $stmt = $pdo->prepare("UPDATE role SET role_name=? WHERE id=?");
        $stmt->execute([
            $role_name,
            $id,
        ]);
        // 删除原权限
        $stmt = $pdo->prepare("DELETE FROM role_privlege WHERE role_id=?");
        $stmt->execute([
            $id
        ]);

        $stmt = $pdo->prepare("INSERT INTO role_privlege(pri_id,role_id) VALUES(?,?)");
        foreach($pri_id as $v)
        {
            $stmt->execute([
                $v,
                $id,
            ]);
        }
    }

    // 删除
    public function delete($id)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $stmt = $pdo->prepare("DELETE FROM role WHERE id=?");
        $stmt->execute([
            $id,
        ]);
        

        $stmt1 = $pdo->prepare("DELETE FROM role_privlege WHERE role_id=?");
        $stmt1->execute([
            $_GET['id'],
        ]);
    }
}