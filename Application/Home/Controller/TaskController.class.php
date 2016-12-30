<?php
namespace Home\Controller;
use Think\Controller;
class TaskController extends Controller{
	 function _initialize() //这里使用contruct是不行的，他并不会默认调用
	 {
		if(empty(session('student_id')))
		{
			$this->error('请登录！',U("Login/login"));
		}
	 
		$type=M('type');
		$this->types=$type->select();//这里的types  就是这个类的成员变量，和tp没有关系，现在应该带着什么样的问题去看面向对象语法，造就了和c++和java php 面向对象的不同，不同有哪些
	   //在每个方法中$this->list  这里的list会把上次的list代替掉
	   
	   
   	}
	//++++++++++++++++++++++++++++++++task部分++++++++++++++++++++++++++++++++++++++++
	// all year month 用的都是year的view
	public function month()//登录学生的本月所有task
	{
		$time=date("Y");//这个用来限制查询时间
		$time_month=date("n");//这个用来限制查询
		$time_maked1=date("Y-m-d",mktime(0,0,0,$time_month,1,$time));
		$time_maked2=date("Y-m-d",mktime(0,0,0,$time_month+1,0,$time));//任何给定月份的最后一天都可以被表示为下个月的第 "0" 天，而不是 -1 天
		$student_id=session('student_id');//这个用来限制查询学生编号
		
		
		$Model=M('');
		$this->list=$Model->query("select task.*,type.name  from task,type where task.type_id=type.id and date_format(date_time1,'%Y-%m-%d')<='$time_maked2' and date_format(time_plan,'%Y-%m-%d')>='$time_maked1' and task.id in ( select task_id from form where student_id=$student_id) order by date_time1");
		$this->display('list_year');
		//这个时间算法，就好比两个棍子滑动是否有交集
	}
	
	public function all()//登录学生的所有task
	{
		$time=date("Y");//这个用来限制查询时间
		$student_id=session('student_id');//这个用来限制查询学生编号
		
		$Model=M('');
		$this->list=$Model->query("select task.*,type.name  from task,type where task.type_id=type.id and task.id in ( select task_id from form where student_id=$student_id) order by date_time1");
		$this->display('list_year');
	}
	
	public function list_year()
	{
		$time=date("Y");//这个用来限制查询时间
		$student_id=session('student_id');//这个用来限制查询学生编号
		
		$Model=M('');
		$this->list=$Model->query("select task.*,type.name  from task,type where task.type_id=type.id and date_format(date_time1,'%Y')<=$time and date_format(time_plan,'%Y')>=$time and task.id in ( select task_id from form where student_id=$student_id) order by date_time1");
		/*select task.*,type.name  from task,type where task.type_id=type.id and date_format(date_time1,'%Y')<='2016' and date_format(time_plan,'%Y')>='2016' order by date_time1           这个是把所有的task都拿出来，没有分人
		在上一条的基础上 加上where 条件    and  task.id in ( select task_id from form where student_id=$student_id)*/
		$this->display();
	}
	public function content_task()
	{
		$task_id=I('task_id');
		$Model=M('');
		$list=$Model->query("select task.*,type.name from task,type where task.type_id=type.id and task.id=$task_id order by date_time1");
		$list=$list[0];
		$this->assign($list);
		$this->display();
	}
	public function edit_task()
	{
		$date['id']=I("id");
		$date['type']=I("type");
		$date['time_plan']=I("time_plan");
		$date['content']=I("content");
		$date['instruction']=I("instruction");
		$task=M('task');
		$result=$task->save($date);
		if($result){    
			$this->success('修改成功', 'list_year');
		} 
		else {       
			$this->error('修改失败');
		}
		
	}
	
	public function add_task()
	{
		
		$this->display();
	}	
	
	public function save_task()
	{
		$date1['type_id']=I("type_id");
		$date1['content']=I("content");
		$date1['instruction']=I("instruction");
		$date1['time_plan']=I("time_plan");//----------------记得改回原状
		$date1['student_id']=session('student_id');//----------------默认是当前登陆的人提交的额任务
		//$date1['date_time2']=time();
		//$content_schedule=I("content_schedule");
		$result_week=$this->week(time());//------------只能周六提交
		if($result_week==false)
		{
			$this->error('添加失败,只能周六添加','list_year');//失败的不会向下执行
		}
			
		$task=M("task");
		$result=$task->add($date1);
		if($result){    
			$this->success('修改成功', 'list_year');
		} 
		else {       
			$this->error('修改失败');
		}
	}
	function delete_task()
	{
		$task_id=I('task_id');
		$task=M("task");
		$result=$task->delete($task_id);
		
		if($result){    
			$this->success('删除任务成功',U('Task/list_year'));//这里只能写成U函数的形式，不能直接写year  tp的bug
		} 
		else {       
			$this->error('删除任务失败');
		}
	}
	
	//++++++++++++++++++++++++++++++++schedule部分++++++++++++++++++++++++++++++++++++++++
	//每一个方法中都会取的当前的task_id 这是因为schedule是task的下一层，与task有对应的关系   放成全局变量也可以
	public function schedule()
	{
		$task_id=I("task_id");
		$schedule=M('schedule');
		$this->list=$schedule->query("select * from schedule where task_id=$task_id");
		$this->task_id=$task_id;
		$this->display();
	}
	
	public function content_schedule()
	{
		$task_id=I("task_id");
		$schedule_id=I('schedule_id');
		$Model=M('');
		$list=$Model->query("select * from schedule where id=$schedule_id");
		$list=$list[0];	
		$this->task_id=$task_id;
		$this->assign($list);
		$this->display();
	}
	public function edit_schedule()
	{
		$task_id=I("task_id");
		$date['id']=I("id");
		$date['content']=I("content");
		$schedule=M('schedule');
		$result=$schedule->save($date);
		
		$path="schedule/task_id/".$task_id;
		if($result){    
			$this->success('修改成功', $path);
		} 
		else {       
			$this->error('修改失败');
		}
		
	}
	public function add_schedule()
	{
		$task_id=I("task_id");//这里一定使用的是""
		
		$this->assign('task_id',$task_id);
		$this->display();
	}
	public function save_schedule()
	{
		$result_week=$this->week(time());//------------只能周六提交
		if($result_week==false)
		{
			$this->error('添加失败，只能周六添加');//失败的不会向下执行
			
		}
		
		$date['task_id']=I('task_id');
		$date['content']=I('content');
		$date['percent']=I('percent');
		$schedule=M('schedule');
		$result=$schedule->add($date);
		$path="schedule/task_id/".$date['task_id'];
		if($result)
		{
			$this->success('添加成功',$path);//成功的转跳会继续向下执行
		}
		else
		{
			$this->error('添加失败');
		}
		
		
		
		
		
	}
	//++++++++++++++++++++++++++其他函数+++++++++++++++++++++++++++++++++
	
	public function week($cu_time)
	{
	    $week = date("w",$cu_time);
		
		if ($week != 6) {
			return false;
		}else{
			return true;
		}
	}
	
}
