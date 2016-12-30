<?php
namespace Admin\Controller;
use Think\Controller;
class VerifyingController extends Controller{
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
		//and task.percent=100 and task.status=2  两个条件
		$list=$task->query("select task.id as task_id ,student.id as student_id,student.name as student_name,type.name as type_name,percent,type.name,content,date_time1,time_plan,instruction,status,task.grade from task,type,student where student.id=task.student_id and task.type_id=type.id and task.percent=100 and task.status=2 order by student_id,percent;");
		
		$this->assign('list',$list);
		$this->display();
	}
	public function do_verifying()
	{
		$task_id=$_GET['task_id'];
		$data['grade']=I("task_grade");
		$data['status']=3;//说明状态发生变化
		$task=M("task");
		$result=$task->where("id = $task_id")->save($data);
/*		echo $task->getLastSql();
		exit;*/
		if($result){    
			$this->success('修改成功', U('Verifying/list_all'));
		} 
		else {       
			$this->error('修改失败');
		}
	}
	
}
