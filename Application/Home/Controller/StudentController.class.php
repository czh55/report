<?php
namespace Home\Controller;
use Think\Controller;
class StudentController extends Controller{
	function _initialize() //这里使用contruct是不行的，他并不会默认调用
	{
		if(empty(session('student_id')))
		{
			$this->error('请登录！',U("Login/login"));
		}
	}
	
	public function info()
	{
		$student['id']=session("student_id");
		$student['password']=session("student_password");
		$student['name']=session("student_name");
		
		$this->assign($student);	
		$this->display();
	}
	
	public function save_student()
	{
		$date['name']=I('name');
		$date['password']=I('password');
		$date['id']=I("id");
		
		$student=M("student");
		$result=$student->save($date);
		
		if($result){  
			session("student_password",$date['password']);
			session("student_name",$date['name']);  
			$this->success('修改成功');//这里为啥一定要加上Home
		} 
		else {       
			$this->error('修改失败');
		}
	}

}