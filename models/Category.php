<?php
namespace models;

class Category extends Model
{
    // 设置这个模型对应的表
    protected $table = 'category';
    // 设置允许接收的字段
    protected $fillable = ['cat_name','parent_id','path'];

    // 取出一个分类的子分类
    // 参数：上级分类的ID
    public function getCat($parent_id = 0)
    {
        return $this->findAll([
            'where' => "parent_id=$parent_id"
        ]);
    }

    public function insert($cat_name,$parent_id,$path)
    {
        $pdo = new \PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $sql = "INSERT INTO category(cat_name,parent_id,path) VALUES(?,?,?)";

        // echo $sql;die;
        $stmt = $pdo->prepare($sql);
        // var_dump($stmt);die;
        $ret = $stmt->execute([
            $cat_name,
            $parent_id,
            $path,
        ]);
        if(!$ret){
            echo "发表失败";
            $error = $stmt->errorInfo();
            echo '<pre>';
            var_dump( $error);
            die;
        }
        return $pdo->lastInsertId();
    }

    public function findOne($id)
    {
        $pdo = new \PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $stmt = $pdo->prepare("SELECT * FROM category WHERE id = ?");
        // var_dump($stmt);die;
        $stmt->execute([
            $id
        ]);
        // 取出数据
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // 修改
    public function update($cat_name,$parent_id,$path,$id)
    {
        $pdo = new \PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $stmt = $pdo->prepare("UPDATE category SET cat_name=?,parent_id=?,path=? WHERE id=?");
        $stmt->execute([
            $cat_name,
            $parent_id,
            $path,
            $id,
        ]);
    }

    // 删除
    public function delete($id)
    {
        $pdo = new \PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $stmt = $pdo->prepare("DELETE FROM category WHERE id=?");
        $stmt->execute([
            $id,
        ]);
    }
}