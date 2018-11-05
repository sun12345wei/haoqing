<?php
namespace controllers;

use models\Admin;

class AdminController extends BaseController{
    // 列表页
    public function index()
    {
        $model = new Admin;
        $data = $model->findAll([
            'fields'=>'a.*,GROUP_CONCAT(c.role_name) role_list',
            'join'=>' a LEFT JOIN admin_role b ON a.id=b.admin_id LEFT JOIN role c ON b.role_id=c.id ',
            'groupby'=>' GROUP BY a.id ',
        ]);
        view('admin/index', $data);
    }

    // 显示添加的表单
    public function create()
    {
        // 取出所有角色的数据
        $model = new \models\Role;
        $data = $model->findAll();
        // echo "<pre>";
        // var_dump($data);die;
        view('admin/create', $data);
    }

    // 处理添加表单
    public function insert()
    {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $role_id = $_POST['role_id'];
        $model = new Admin;
        $stmt = $model->insert($username,$password,$role_id);
        redirect('/admin/index');
    }

    // 显示修改的表单
    public function edit()
    {
        $model = new Admin;
        $data=$model->findOne($_GET['id']);
        // var_dump($data);die;
        // 取出这个角色所拥有的权限ID
        $priIds = $model->getPriIds($_GET['id']);
        // var_dump($priIds);die;
        // var_dump($data,$priIds);die;
        view('admin/edit', [
            'data' => $data,
            'priIds'=>$priIds,
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        $username = $_POST['username'];
        $role_id = $_POST['role_id'];
        $id = $_GET['id'];
        $model = new Admin;
        $stmt = $model->update($username,$id,$role_id);
        redirect('/admin/index');
    }

    // 删除
    public function delete()
    {
        $model = new Admin;
        $model->delete($_GET['id']);
        redirect('/admin/index');
    }
}