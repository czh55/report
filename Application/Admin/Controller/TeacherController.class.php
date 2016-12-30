<?php
namespace Admin\Controller;
use Think\Controller;
class TeacherController extends Controller{
	function _initialize() //这里使用contruct是不行的，他并不会默认调用
	{
		if(empty(session('teacher_id')))
		{
			$this->error('请登录！',U("Login/login"));
		}
	}
	
	public function info()
	{
		$teacher['id']=session("teacher_id");
		$teacher['password']=session("teacher_password");
		$teacher['name']=session("teacher_name");
		
		$this->assign($teacher);	
		$this->display();
	}
	
	public function save_teacher()
	{
		$date['name']=I('name');
		$date['password']=I('password');
		$date['id']=I("id");
		
		$teacher=M("teacher");
		$result=$teacher->save($date);
		
		if($result){  
			session("teacher_password",$date['password']);
			session("teacher_name",$date['name']);  
			$this->success('修改成功');//这里为啥一定要加上Home
		} 
		else {       
			$this->error('修改失败');
		}
	}

}