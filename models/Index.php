<?php
namespace Models;
use PDO;
class Index
{
    // 保护 PDO 对象
    public $pdo;
    public function __construct()
    {
        // 取日志的数据
        $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $this->pdo->exec('SET NAMES utf8');
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM blogs where id = ?");
        $stmt->execute([
            $id
        ]);
        $info = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->pdo->prepare("SELECT * FROM blogs_image where blogs_id = ?");
        $stmt->execute([
            $id
        ]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($images);die;
        // 返回所有数据
        return [
            'info'=>$info,
            'images'=>$images,
        ];
    }

    // 修改
    public function update($cat1_id,$cat2_id,$cat3_id,$title,$is_show,$zuozhe,$path,$content,$brand_id,$id)
    {
      
        $stmt = $this->pdo->prepare("UPDATE blogs SET cat1_id=?,cat2_id=?,cat3_id=?,title=?,is_show=?,register_id=?,content=?,brand_id=? WHERE id=?");
        $ret = $stmt->execute([
            $cat1_id,
            $cat2_id,
            $cat3_id,
            $title,
            $is_show,
            $zuozhe,
            $content,
            $brand_id,
            $id,
        ]);

        // $stmt2 = $pdo->query("select last_insert_id() as blogs_id");
        // $data = $images['blogs_id'];

        $stmt1 = $this->pdo->prepare("UPDATE blogs_image SET path=? WHERE blogs_id=? and id = ?");
        var_dump($path);
       
        foreach($path['new_img'] as $k=>$v){
            // echo $v; die;
            $iamges = $stmt1->execute([
                $v,
                $id,
                $path['img_id'][$k]
            ]);
        }
    }

    // 删除
    public function delete($id)
    {

        $stmt = $this->pdo->prepare("DELETE FROM blogs WHERE id = ?");
        $stmt->execute([
            $id,
        ]);
    }

}