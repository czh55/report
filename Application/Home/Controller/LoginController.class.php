<?php
namespace Home\Controller;
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
		
		
		
		$student=M("student");
		$result=$student->where("name='$name' and password='$password'")->find();
		if($result)
		{
			session('student_name',$name);//以后要养成习惯，session中存值时要声明所属的类别如这里的student
			session('student_id',$result['id']);
			session('student_password',$password);
			$this->success("登录成功",U("Home/Task/list_year"));
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