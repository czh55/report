<?php
namespace Home\Controller;
use Think\Controller;
class VerifyController extends Controller{
	 function _initialize() //这里使用contruct是不行的，他并不会默认调用
	 {
		if(empty(session('student_id')))
		{
			$this->error('请登录！',U("Login/login"));
		}
	   
   	}
	
	public function percent100()
	{
		$student_id=session('student_id');//这个用来限制查询学生编号
		
		$Model=M('');
		//task.percent=100 and task.status=0  查询条件两个个条件
		$this->list=$Model->query("select task.*,type.name  from task,type where task.type_id=type.id and task.id in ( select task_id from form where student_id=$student_id) and task.percent=100 and task.status=0 order by date_time1");
		
		$this->display();
	}
	
	public function do_percent100()//要区是否要提交给老师，还是自己处理
	{
		/*echo $task_id=I("task_id");*/
		$task_id=$_GET['task_id'];
		$checkbox=I("checkbox");
		if($checkbox==1)
		{
			$data['status']=2;
		}
		else
		{
			$data['status']=1;
		}
		
		$data['grade']=I("task_grade");
		$task=M("task");
		$result=$task->where("id = $task_id")->save($data);
/*		echo $task->getLastSql();
		exit;*/
		if($result){    
			$this->success('修改成功', U('Verify/percent100'));
		} 
		else {       
			$this->error('修改失败');
		}
		
		
	}
	
	public function verifying()
	{
		$student_id=session('student_id');//这个用来限制查询学生编号
		
		$Model=M('');
		//task.percent=100 and task.status=2  查询条件2个条件
		$this->list=$Model->query("select task.*,type.name  from task,type where task.type_id=type.id and task.id in ( select task_id from form where student_id=$student_id) and task.percent=100 and task.status=2 order by date_time1");
		
		$this->display();
	}
	public function finish()
	{
		$student_id=session('student_id');//这个用来限制查询学生编号
		
		$Model=M('');
		//task.percent=100 and (task.status=3 or task.status=1)查询条件三个条件
		$this->list=$Model->query("select task.*,type.name  from task,type where task.type_id=type.id and task.id in ( select task_id from form where student_id=$student_id) and task.percent=100 and (task.status=3 or task.status=1) order by date_time1");
		
		$this->display("Verify:verifying");
	}
	
}
