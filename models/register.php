<?php
namespace models;

class register
{
    public function add($class,$title,$logo,$content,$is_show)
    {
        $stmt = self::$pdo->prepare("INSERT INTO blogs(class,title,logo,content,is_show,register_id) VALUES(?,?,?,?,?,?)");
        $ret = $stmt->execute([
            $classif,
            $title,
            $logo,
            $content,
            $is_show,
            $_SESSION['id'],
        ]);
        if(!$ret)
        {
            echo '失败';
            // 获取失败信息
            $error = $stmt->errorInfo();
            var_dump($error);
            exit;
        }
        // 返回新插入的记录的ID
        return self::$pdo->lastInsertId();
    }
}