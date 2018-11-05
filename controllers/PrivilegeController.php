<?php
namespace controllers;

use models\Privilege;

class PrivilegeController extends BaseController
{
    // 列表页
    public function index()
    {
        $model = new Privilege;
        // $data = $model->tree();
        $data = $model->findAll();
        $btns = $model->design();
        view('privilege/index', [
            'data'=>$btns['data'],
            'btns'=>$btns['btns']
        ]);
    }

    // 显示添加的表单
    public function create()
    {
        view('privilege/create');
    }

    // 处理添加表单
    public function insert()
    {
        $pri_name = $_POST['pri_name'];
        $url_path = $_POST['url_path'];
        $parent_id = $_POST['parent_id'];
        $model = new Privilege;
        $stmt = $model->insert($pri_name,$url_path,$parent_id);
        redirect('/privilege/index');
    }

    // 显示修改的表单
    public function edit()
    {
        $model = new Privilege;
        $data=$model->findOne($_GET['id']);
        view('privilege/edit', [
            'data' => $data,    
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        $pri_name = $_POST['pri_name'];
        $url_path = $_POST['url_path'];
        $parent_id = $_POST['parent_id'];
        $id = $_GET['id'];
        $model = new Privilege;
        $stmt = $model->update($pri_name,$url_path,$parent_id,$id);
        redirect('/privilege/index');
    }

    // 删除
    public function delete()
    {
        $model = new Privilege;
        $model->delete($_GET['id']);
        redirect('/privilege/index');
    }
}