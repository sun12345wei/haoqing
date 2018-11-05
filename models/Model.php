<?php
namespace models;

class model
{
    /**
     * 钩子函数
     */

    protected function _before_write(){}
    protected function _after_write(){}
    protected function _before_delete(){}
    protected function _after_delete(){}

    public function findAll($options = [])
    {
        $pdo = new \PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');

        $_option = [
            'fields' => '*',
            'where' => 1,
            'order_by' => 'id',
            'order_way' => 'desc',
            'per_page'=>20,
            'join'=>'',
            'groupby'=>'',
        ];

        // 合并用户的配置
        if($options)
        {
            $_option = array_merge($_option, $options);
        }

        /**
         * 翻页
         */
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page-1)*$_option['per_page'];
            
        $sql = "SELECT {$_option['fields']}
                FROM {$this->table}
                {$_option['join']}
                WHERE {$_option['where']} 
                {$_option['groupby']}
                ORDER BY {$_option['order_by']} {$_option['order_way']} 
                LIMIT $offset,{$_option['per_page']}";
                // echo $sql;
                // die;
        $stmt = $pdo->query($sql);
        // $stmt->execute();
        return $data = $stmt->fetchAll(\PDO::FETCH_ASSOC );
    }

    // 递归排序（本类和子本可以调用（protected））
    // 参数一、排序的数据
    // 参数二、上级ID
    // 参数三、第几级
    protected function _tree($data, $parent_id=0, $level=0)
    {
        // 定义一个数组保存排序好之后的数据
        static $_ret = [];
        foreach($data as $v)
        {
            if($v['parent_id'] == $parent_id)
            {
                // 标签它的级别
                $v['level'] = $level;
                // 挪到排序之后的数组中
                $_ret[] = $v;
                // 找 $v 的子分类
                $this->_tree($data, $v['id'], $level+1);
             }
        }
        // 返回排序好的数组
        return $_ret;
    }
}