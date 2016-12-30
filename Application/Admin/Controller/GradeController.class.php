<?php
namespace Admin\Controller;
use Think\Controller;
class GradeController extends Controller{
	 function _initialize() //这里使用contruct是不行的，他并不会默认调用
	 {
		if(empty(session('teacher_id')))
		{
			$this->error('请登录！',U("Login/login"));
		}
   	}
	
	public function list_all()
	{
				
		$Model=M('');
		//条件 (task.status=1 or task.status=3) and task.percent=100 因为要区分开老师还没有审核时学生的自己填的分数
		$this->list=$Model->query("select student.name as student_name,sum(grade) as sum_grade,count(*) count_task from task,student  where task.student_id=student.id  and (task.status=1 or task.status=3) and task.percent=100 group by student_id order by sum_grade;");
		
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
