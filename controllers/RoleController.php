<?php
namespace controllers;

use models\Role;

class RoleController extends BaseController{
    // 列表页
    public function index()
    {
        $model = new Role;
        $data = $model->findAll([
            'fields'=>'a.*,GROUP_CONCAT(c.pri_name) pri_list',
            'join'=>' a LEFT JOIN role_privlege b ON a.id=b.role_id LEFT JOIN privilege c ON b.pri_id=c.id ',
            'groupby'=>' GROUP BY a.id ',
        ]);
        view('role/index', $data);
    }

    // 显示添加的表单
    public function create()
    {
        // 取出所有的权限
        $priModel = new \models\Privilege;
        // $data = $priModel->findAll();
        $data = $priModel->tree();

        // 显示表单
        view('role/create', [
            'data' => $data,
        ]);
    }

    // 处理添加表单
    public function insert()
    {
        $role_name = $_POST['role_name'];
        $pri_id = $_POST['pri_id'];
        $model = new Role;
        $stmt = $model->insert($role_name,$pri_id);
        redirect('/role/index');
    }

    // 显示修改的表单
    public function edit()
    {
        $model = new Role;
        $data=$model->findOne($_GET['id']);

        // 取出权限的数据
        $priModel = new \models\Privilege;
        // 获取树形数据（递归排序好的）
        $priData = $priModel->tree();
        // 取出这个角色所拥有的权限ID
        $priIds = $model->getPriIds($_GET['id']);

        // echo '<pre>';
        // var_dump( $priIds );

        view('role/edit', [
            'data' => $data,
            'priData'=>$priData,
            'priIds'=>$priIds,    
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        $role_name = $_POST['role_name'];
        $pri_id = $_POST['pri_id'];
        $id = $_GET['id'];
        $model = new Role;
        $stmt = $model->update($role_name,$pri_id,$id);
        redirect('/role/index');
    }

    // 删除
    public function delete()
    {
        $model = new Role;
        $model->delete($_GET['id']);
        redirect('/role/index');
    }
}