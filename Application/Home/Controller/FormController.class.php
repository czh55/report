<?php
namespace Home\Controller;
use Think\Controller;
class FormController extends Controller{
	function _initialize() //这里使用contruct是不行的，他并不会默认调用
	{
		if(empty(session('student_id')))
		{
			$this->error('请登录！',U("Login/login"));
		}
	}
	//假设登陆的人为5号学生
	//++++++++++++++++++++++++++小组的+++++++++++++++++++++++++++++++++++++++++++++
	public function form()
	{
		$student_id=session('student_id');
		$model=M('');
		$this->list=$model->query("select task.*,type.name  from task,type where task.type_id=type.id and task.id in (select task_id from form where task_id in (select task_id from form group by task_id having count(task_id)>1) and student_id=$student_id) order by task.id desc");//五号 独立完成5  合作完成6  diff=0 默认为小组
		$this->display();
		//5号学生 完成的多人任务
		/*select task_id from form group by task_id having count(task_id)>1  所有多人完成，并且去重复的task_id
		select task_id from form where task_id in (上一句) where student_id=5  恢复重复，使学生数不要减少 并选择student_id=5的学生 的task_id*/
		
	}
	
	public function students()
	{
		$task_id=I("task_id");
		$model=M("");
		$this->list=$model->query("select * from student where id in(select student_id from form where task_id=$task_id)");
		
		$this->task_id=$task_id;//同样是为了保存于task的对应关系
		$this->display();
	}
	
	public function delete_student()
	{
		$task_id=I('task_id');
		$student_id=I("student_id");
		$form=M("form");
		$result=$form->where("student_id=$student_id and task_id=$task_id")->delete();//这一定要加上task_id 否则会删好几条数据
		if($result){    
			$this->success('删除成员成功');
		} 
		else {       
			$this->error('删除成员失败');
		}
	}
	public function add_student()
	{
		$task_id=I("task_id");
		$student=M("student");
		
		$this->list=$student->select();
		$this->task_id=$task_id;
		$this->display();
	}
	
	public function save_student()
	{
		$date['task_id']=I("task_id");
		$date['student_id']=I("student_id");
		
		$form=M("form");
		$result=$form->add($date);
		if($result){    
			$this->success('添加成员成功',U("Home/Form/students/task_id/".$date['task_id']));//这里为啥一定要加上Home
		} 
		else {       
			$this->error('添加成员失败');
		}
	}
	
	
	//+++++++++++++++++++++++++++++个人的+++++++++++++++++++++++++++++++++++++++++++++++++
	public function own()
	{
		$student_id=session('student_id');
		$model=M('');
		$this->list=$model->query("select task.*,type.name  from task,type where task.type_id=type.id and task.id in (select task_id from form where task_id in (select task_id from form group by task_id having count(task_id)=1) and student_id=$student_id) order by task.id desc");//五号 独立完成5  合作完成6  diff=0 默认为小组
		$this->display();
	}
	//+++++++++++++++++++++++++++++无人管理的项目+++++++++++++++++++++++++++++++++++++++++++
	/*public function none()
	{
		$student_id=session('student_id');
		$model=M('');
		$this->list=$model->query("select task.*,type.name from task,type where task.type_id=type.id and task.student_id=$student_id and task.id not in (select task_id from form)");
		$this->display();
		
	}
	public function save_own()
	{
		$date['task_id']=I("task_id");
		$date['student_id']=$student_id=session('student_id');//------------------这里加上登录之后记得改了
		
		$form=M("form");
		$result=$form->add($date);
		if($result){    
			$this->success('添加成员成功',U("Home/Form/students/task_id/".$date['task_id']));//这里为啥一定要加上Home//这了跳到了students是为了方便用户添加人
		} 
		else {       
			$this->error('添加成员失败');
		}
	}*/
}