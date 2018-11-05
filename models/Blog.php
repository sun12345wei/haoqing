<?php
namespace models;
use PDO;

class Blog extends Model
{
    public function add($cat1_id,$cat2_id,$cat3_id,$title,$is_show,$zuozhe,$path,$content,$brand_id){
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec("SET NAMES utf8");
        $sql = "INSERT INTO blogs(cat1_id,cat2_id,cat3_id,title,is_show,register_id,content,brand_id) VALUES(?,?,?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $ret = $stmt->execute([
            $cat1_id,
            $cat2_id,
            $cat3_id,
            $title,
            $is_show,
            $zuozhe,
            $content,
            $brand_id,
        ]);
        $stmt2 = $pdo->query("select last_insert_id() as blog_id");
      
        $data = ($stmt2->fetch(PDO::FETCH_ASSOC))['blog_id'];
        
        $sql1 = "INSERT INTO blogs_image(blogs_id,path) VALUES(?,?)";

        $stmt1 = $pdo->prepare($sql1);
        foreach($path as $v){

            $iamges = $stmt1->execute([
                $data,
                $v,
            ]);

        }

        



      
        
        // die();
        
    }
}