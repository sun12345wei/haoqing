<?php
namespace Controllers;
use Intervention\Image\ImageManagerStatic as Image;

class BlogController extends BaseController
{
    public function store()
    {
        $cat1_id = $_POST['cat1_id'];
        $cat2_id = $_POST['cat2_id'];
        $cat3_id = $_POST['cat3_id'];
        $title = $_POST['title'];
        $is_show = $_POST['is_on_sale'];
        $zuozhe = $_POST['author'];
        $path = $_FILES['path']['name'];
        // var_dump($_FILES);
        // die;
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
        $imggage = [];
        foreach($path as $k => $v)
        {
            // 生成唯一的名字
            $name = md5( time() . rand(1,9999) );
            // 先取出原来这个图片的后缀
            $ext = strrchr($v, '.');
            // 补上扩展名
            $name = $name . $ext;
            // 移动图片
            move_uploaded_file($_FILES['path']['tmp_name'][$k], $root . $date . '/' . $name);
            // echo $root . $date .'/' . $name . '<hr>';die;
            $imgage[]= '/uploads/' . $date .'/' . $name;
            // return $imgage;
        }
        $content = $_POST['content'];
        $brand_id = $_POST['brand_id'];

        $blog = new \models\Blog;
        $stmt = $blog->add($cat1_id,$cat2_id,$cat3_id,$title,$is_show,$zuozhe,$imgage,$content,$brand_id);

        $img = ROOT.'public/'.$path;


        foreach($imgage as $k => $v){

            $date = date('Ymd');
            //图片类，参数里面路径
            
            $image = Image::make(ROOT.'public/'.$v);
            //图片名字的后缀
            $ext = strrchr($v, '.');
            //缩略图的随机名字
            $t1 = md5(time().rand(10000,99999999));
            $t2 = md5(time().rand(10000,99999999));
            $t3 = md5(time().rand(10000,99999999));
            //新建缩略图的保存文件夹
            if(!is_dir(ROOT.'public/thumb_img/'.date('Y-m-d'))){
                mkdir(ROOT.'public/thumb_img/'.date('Y-m-d'),0777,true);
            }
            //生成缩略图
            $image->resize(50,50)->save(ROOT.'public/thumb_img/'.date('Y-m-d')."/".$t1.$ext);

            $image->resize(80,80)->save(ROOT.'public/thumb_img/'.date('Y-m-d')."/".$t2.$ext);

            $image->resize(150,150)->save(ROOT.'public/thumb_img/'.date('Y-m-d')."/".$t3.$ext);
        
        }
        // var_dump($path);die;
        
        // var_dump($stmt);
        // echo "添加成功！";
        redirect('/index/design');
    }

    public function insert()
    {
        // 取出一级分类
        $model = new \models\Category;
        $topCat = $model->getCat();

        // var_dump( $topCat );die;

        view('blog/insert', [
            'topCat' => $topCat,
        ]);
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
}