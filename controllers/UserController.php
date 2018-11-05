<?php
namespace controllers;

class UserController
{
    public function index()
    {
        // var_dump($_POST);
        // 1. 接收表单
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        // var_dump($username,$password);
        // die;

        // 2. 插入到数据库中
        $user = new \models\User;
        $user = $user->index($username,$password);
        if($user)
        {

            redirect('/');
        }
        else
        {
            redirect('/login/index');
        }
    }

    // 退出
    public function logout()
    {
        
        $user = new \models\User;
        $user->logout();
    }



    // public function 方法(){
    //     $username = $_POST['username'];
    //     $password = md5($_POST['password']);
       
        //接收值
        //echo $username,$password;
        // $model=M('register');
        // $info=$model->where("username='$username'")->find();
        // if($info){
        //  //获取当前时间
        //  $now=date("Y-m-d");
       
        //以下是试验测试
        // $now=date("Y-m-d",strtotime("-1 day")); 
        // echo "昨天:",date("Y-m-d",strtotime("-1 day")), "";  die;
      
        //echo "".strtotime($info['time'])."";die;
        //当前“时间戳”减去数据库里的“时间戳”
        // $cha=strtotime($now)-strtotime($info['time']);
        //echo $cha;die;
        //当已经”解锁“时
        // if($cha>=86400){
        //  //解锁时间如果到了，清除以前的记录数，还原0
        //  if($info['num']!='0'){
        //       $data['num']='0';
        //       $arr=$model->where("username='$username'")->save($data);
        //    }else{
        //       $arr=1;
        //    }
        //   if($arr){ 
        //   //如果密码争取则显示成功跳转页面
        //    if($info['password']==$password){
        //        $this->success("登陆成功！","返回路径");
        //     }else{
        //        //如果密码错了则找到对应的用户名
        //        $info1=$model->where("username='$username'")->find();
        //        $data['time']=date("Y-m-d");
        //        //使数据库里面的”num+1“
        //        $data['num']=$info1['num']+1;
        //        $times=3-$data['num'];
        //        $model->where("username='$username'")->save($data);
        //     if($times>0){
        //        $this->error("密码错误！你还剩".$times."次机会");
        //      } 
        //      die;
        //     }
        //    }
        //   }
        // //在解锁时间内，但是不良记录已经达到3次，也是不可以登录的；
        // if($cha<86400 && $info['num']==3){
        //    $this->error("你的账号已锁定，请明天登录！");
        //    die;
        //  }
        // //在解锁时间内，并且不良记录未满3次，可以登录；
        // if($cha<86400 && $info['num']<3){
        //    if($info['password']==$password){
        //         $this->success("登陆成功！","返回路径");
        //     }else{
        //        //如果密码错了则找到对应的用户名
        //        $info1=$model->where("username='$username'")->find();
        //        $data['time']=date("Y-m-d");
        //       //使数据库里面的”num+1“
        //       $data['num']=$info1['num']+1;
        //       $times=3-$data['num'];
        //       $model->where("username='$username'")->save($data);
        //     if($times>0){
        //         $this->error("密码错误！你还剩".$times."次机会");
        //      }else{
        //         $this->error("密码错误次数已达3次，账号即将锁定！");
        //      }
        //    }
        //  }
        // }else{
        //   echo "用户名错误！";
        // }
    // }
    
}