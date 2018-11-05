<?php
namespace models;

use PDO;

class Privilege extends Model
{
    // 设置这个模型对应的表
    protected $table = 'privilege';
    // 设置允许接收的字段
    protected $fillable = ['pri_name','url_path','parent_id'];

    // 递归树形结构的数据
    public function tree()
    {
        // 先取出所有的权限
        $data = $this->findAll([
            'per_page'=>999999
        ]);
        
        // 递归重新排序
        $ret = $this->_tree($data);
        return $ret;
    }

    public function insert($pri_name,$url_path,$parent_id)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $sql = "INSERT INTO privilege(pri_name,url_path,parent_id) VALUES(?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $pri_name,
            $url_path,
            $parent_id,
        ]);
    }

    public function findOne($id)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $stmt = $pdo->prepare("SELECT * FROM privilege WHERE id=?");
        $stmt->execute([$id]);
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);
        return $ret;
    }

    // 修改
    public function update($pri_name,$url_path,$parent_id,$id)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $stmt = $pdo->prepare("UPDATE privilege SET pri_name=?,url_path=?,parent_id=? WHERE id=?");
        $stmt->execute([
            $pri_name,
            $url_path,
            $parent_id,
            $id,
        ]);
    }

    // 删除
    public function delete($id)
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');
        $stmt = $pdo->prepare("DELETE FROM privilege WHERE id=?");
        $stmt->execute([
            $id,
        ]);
    }

    // 分页
    public function design()
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');

        // 设置的 $where
        $where = 1;

        // 放预处理对应的值
        $value = [];

        $perpage = 9; // 每页5条
        // 接收当前页码
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        // 计算开始的下标
        $offset = ($page-1)*$perpage;

        // 制作按钮
        // 取出总的记录数
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM privilege WHERE $where");
        $stmt->execute($value);
        $count = $stmt->fetch( PDO::FETCH_COLUMN );
        // 计算总的页数
        $pageCount = ceil( $count / $perpage );

        $btns = '';
        for($i=1;$i<=$pageCount;$i++)
        {
            // 获取之前的参数
            $params = getUrlParams(['page']);

            $class = $page==$i ? 'active' : '';
            $btns .= "<a class='$class' href='?{$params}page=$i'> $i </a>";
        }

        // 执行SQL
        $stmt = $pdo->query("SELECT * FROM privilege WHERE $where LIMIT $offset,$perpage");
        // 取出数
  
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'btns'=>$btns,
            'data'=>$data
        ];
    }
}