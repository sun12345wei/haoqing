<?php
namespace controllers;

class IndexController extends BaseController
{
    public function index()
    {
        
        view('index/index');
    }

    // 列表
    public function design()
    {
        // 取出列表的数据
        $pdo = new \PDO('mysql:host=127.0.0.1;dbname=haoqing', 'root', '');
        $pdo->exec('SET NAMES utf8');

        // 设置的 $where
        $where = 1;

        // 放预处理对应的值
        $value = [];

        // 搜索
        // 如果有kewords 并值不为空时
        if(isset($_GET['keywords']) && $_GET['keywords'])
        {
            $where .= " AND (title LIKE '%{$_GET['keywords']}%' OR content LIKE '%{$_GET['keywords']}%')";
        }

        if(isset($_GET['is_show']) && ($_GET['is_show']==='0' || $_GET['is_show']==1))
        {
            $where .= " AND is_show = '{$_GET['is_show']}'";
        }

        /**********************排序****************************/
        // 默认排序
        $orderBy = 'created_at';
        $orderWay = 'desc';

        // 设置排序字段
        if(isset($_GET['order_by']) && $_GET['order_by'] == 'brand_id')
        {
            $orderBy = 'brand_id';
        }

        if(isset($_GET['order_way']) && $_GET['order_way'] == 'asc')
        {
            $orderWay = 'asc';
        }

        /********************翻页**************************/
        $perpage = 5; // 每页15条
        // 接收当前页码
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        // 计算开始的下标
        $offset = ($page-1)*$perpage;

        // 制作按钮
        // 取出总的记录数
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE $where");
        $stmt->execute($value);
        $count = $stmt->fetch( \PDO::FETCH_COLUMN );
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

        /**********************执行SQL************************/
        // 执行SQL
        $stmt = $pdo->query("SELECT * FROM blogs WHERE $where ORDER BY $orderBy $orderWay LIMIT $offset,$perpage");
        // 取出数据
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        // 加载视图
        view('index/design', [
            'data' => $data,
            'btns' => $btns,
        ]);
    }

    // 修改
    public function edit()
    {
        $id = $_GET['id'];
        // 根据ID取出日志的信息
        $model = new \models\Index;
        $data = $model->find( $id );

        // var_dump($data);
        // die;

        // 取出一级分类
        $model = new \models\Category;
        $topCat = $model->getCat();
        
        view('blog/edit',[
            "data" => $data,
            "topCat" => $topCat,
        ]);
    }

    public function update()
    {
        $cat1_id = $_POST['cat1_id'];

        $cat2_id = $_POST['cat2_id'];

        $cat3_id = $_POST['cat3_id'];

        $title = $_POST['title'];

        $is_show = $_POST['is_on_sale'];

        $zuozhe = $_POST['author'];

        $new_img = $_FILES['path'];

        $id = $_GET['id'];

         // 先创建目录
         $root = ROOT.'public/uploads/';
         // 今天日期
         $date = date('Ymd');
         // 如果没有这个目录就创建目录
         if(!is_dir($root . $date))
         {
             // 创建目录
             mkdir($root . $date, 0777);
         }

          // 根据ID取出日志的信息

        $model = new \models\Index;

        $old_img = ($model->find( $id ))['images'];
         
        
        $imgage['new_img']=[];
        $imgage['img_id']= [];
         
        foreach($new_img['name'] as $k=>$v){

            if(!$new_img['size'][$k]==0){

                unlink(ROOT . 'public'. $old_img[$k]['path']);

              // 生成唯一的名字
              $name = md5( time() . rand(1,9999) );
              // 先取出原来这个图片的后缀
              $ext = strrchr($v, '.');
             
              // 补上扩展名
              $name = $name . $ext;
              // 移动图片s

              move_uploaded_file($new_img['tmp_name'][$k], $root . $date . '/' . $name);
              
              $imgage['new_img'][]= '/uploads/' . $date .'/' . $name;
              $imgage['img_id'][]= $old_img[$k]['id'];
            
            }
        }

        $content = $_POST['content'];
        $brand_id = $_POST['brand_id'];
        $id = $_GET['id'];
        $model = new \models\Index;
        $model->update($cat1_id,$cat2_id,$cat3_id,$title,$is_show,$zuozhe,$imgage,$content,$brand_id,$id);
        if(!$model){
            echo "修改失败";
            $error = $stmt->errorInfo();
            echo '<pre>';
            var_dump( $error); 
        }
        redirect('/index/design');
    }

    // 获取子分类
    public function ajax_get_cat()
    {
        $id = (int)$_GET['id'];
        // 根据这个ID查询子分类
        $model = new \models\Category;
        $data = $model->getCat($id);
        // 转成 JSON
        echo json_encode($data);
    }

    // 删除
    public function delete()
    {
        $id = $_GET['id'];
        // 根据ID取出日志的信息
        $model = new \models\Index;
        $data = $model->find( $id );
        
        if($logo['error']!=4)
        {
           unlink(ROOT . 'public'. $data['logo']);
        }

        $model = new \models\Index;
        $model->delete($id);
        redirect('/index/design');
        
    }
}