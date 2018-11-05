<?php
namespace controllers;

use models\Category;

class CategoryController extends BaseController
{
    // 列表页
    public function index()
    {
        $model = new Category;

        // $tree = $model->tree();

        // $data = $model->findAll([
        //     'order_by' => 'concat(path,id,"-")',
        //     'order_way' => 'asc',
        //     'per_page' => 999999999,    // 不翻页
        // ]);
        $data = $model->findAll();
        
        view('category/index', $data);
    }

    // 显示添加的表单
    public function create()
    {
        view('category/create');
    }

    // 处理添加表单
    public function insert()
    {
        $cat_name = $_POST['cat_name'];
        $parent_id = $_POST['parent_id'];
        $path = $_POST['path'];
        $categroy = new \models\category;
        $stmt = $categroy->insert($cat_name,$parent_id,$path);
        redirect('/category/index');

    }

    // 显示修改的表单
    public function edit()
    {
        $model = new \models\Category;
        $data = $model->findOne($_GET['id']);
        view('category/edit', [
            'data' => $data,    
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        $cat_name = $_POST['cat_name'];
        $parent_id = $_POST['parent_id'];
        $path = $_POST['path'];
        $id = $_GET['id'];
        $categroy = new \models\category;
        $data = $categroy->findOne($id);
        $stmt = $categroy->update($cat_name,$parent_id,$path,$id);
        redirect('/category/index');
    }

    // 删除
    public function delete()
    {
        $model = new Category;
        $data = $model->findOne($_GET['id']);
        $model->delete($_GET['id']);
        redirect('/category/index');
    }
}