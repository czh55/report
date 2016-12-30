<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
	public function login()
	{
		$this->display();
	}
		
	public function do_login()
	{
		$name=I('name');
		$password=I('password');
		
		
		
		$teacher=M("teacher");
		$result=$teacher->where("name='$name' and password='$password'")->find();
		if($result)
		{
			session('teacher_name',$name);//以后要养成习惯，session中存值时要声明所属的类别如这里的teacher
			session('teacher_id',$result['id']);
			session('teacher_password',$password);
			$this->success("登录成功",U("Admin/Percent/list_all"));
		}
		else
			$this->error("登录失败");
		{
		}
	}
	public function login_out()
	{
		session(null); 
		$this->display("Login:login");
	}
	
	
	
	
	
}