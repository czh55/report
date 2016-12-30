<?php
namespace Admin\Controller;
use Think\Controller;
class PercentController extends Controller{
	 function _initialize() //这里使用contruct是不行的，他并不会默认调用
	 {
		if(empty(session('teacher_id')))
		{
			$this->error('请登录！',U("Login/login"));
		}
   	}
	
	public function list_all()
	{
		$task=M("task");
		$list=$task->query("select task.id as task_id ,student.id as student_id,student.name as student_name,type.name as type_name,percent,type.name,content,date_time1,time_plan,instruction,status,task.grade from task,type,student where student.id=task.student_id and task.type_id=type.id  order by student_id,percent;");
		
		$this->assign('list',$list);
		$this->display();
	}
	public function list_part()
	{
		$task=M("task");
		$student_name=trim(I("student_name"));//去掉两边空格
		$task_percent_down=I("task_percent_down");
		$task_percent_up=I("task_percent_up");
		
		if(empty(I("date_time1")))
		{
			$date_time1='0000-00-00';
		}
		else
		{
			$date_time1=I("date_time1");
		}
		
		//可以查询的内容有  $studdent_name姓名   $task_percent_dowm--$task_percent_up进度范围   $date_time1以后的任务
		$list=$task->query("select task.id as task_id ,student.id as student_id,student.name as student_name,type.name as type_name,percent,type.name,content,date_time1,time_plan,instruction,status,task.grade from task,type,student where student.id=task.student_id and task.type_id=type.id  and student.name like '%$student_name%'  and task.percent <=$task_percent_up and task.percent >=$task_percent_down  and date_time1>$date_time1 order by student_id,percent;");
		$this->assign('list',$list);
		$this->display("Percent:list_all");
	}
	
	public function schedule()
	{
		$task_id=I("task_id");
		$schedule=M('schedule');
		$this->list=$schedule->query("select * from schedule where task_id=$task_id");
		$this->task_id=$task_id;
		$this->display();
	}
	
}
