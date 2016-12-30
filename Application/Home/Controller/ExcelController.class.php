<?php
namespace Home\Controller;
use Think\Controller;
class ExcelController extends Controller{	
	function _initialize() //这里使用contruct是不行的，他并不会默认调用
	{
		if(empty(session('student_id')))
		{
			$this->error('请登录！',U("Login/login"));
		}
	}
	public function check()
	{
		$time=date("Y");
		$date[1]=$time;
		$date[2]=$time-1;
		$date[3]=$time-2;
		$date[4]=$time-3;
		
		$this->assign('date',$date);
		$this->display();
		
	}
	/*1test
	2all
	3target
	4schedule
	5task
	6week*/
	public function test()
	{
		$k='A';
		echo ++$k;
	
	}
	public function all()
	{
		/*$i = 'A';
		$i++;
		echo $i;*/
		if(I("dif")!="dif")
		{
			$time=date("Y");//这个用来限制查询时间
		}
		else
		{
			echo $time=I('time');
		
		}
		$this->time=$time;//变为全局变量
		$student_id=session('student_id');//这个用来限制查询学生编
		
		$Model=M('');
		/*$number=$Model->query("select count(*) as num from task where date_format(date_time1,'%Y')<=$time and date_format(date_time2,'%Y')>=$time");*/
		$number=$Model->query("select count(*) as num from task where date_format(date_time1,'%Y')<=$time and date_format(date_time2,'%Y')>=$time and task.id in ( select task_id from form where student_id=$student_id)");
		
		vendor("PHPExcel.PHPExcel");//如果这里提示类不存在，肯定是你文件夹名字不对。
		$objPHPExcel = new \PHPExcel();//这里要注意‘\’ 要有这个。因为版本是3.1.2了。
		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);//设置保存版本格式
		
		$objWriteHTML = new \PHPExcel_Writer_HTML($objPHPExcel);
		$objPHPExcel->getActiveSheet()->getStyle('A1:R100')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//文本居中
		//$sheet->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//在tp3.2中不能有\
		$objPHPExcel->getActiveSheet()->getStyle('A1:R100')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('A1:R100')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中 
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(30);
		
		$objPHPExcel->getActiveSheet()->setCellValue('B1',  '序号');		
		$objPHPExcel->getActiveSheet()->setCellValue('C1',  '任务');				
		$objPHPExcel->getActiveSheet()->setCellValue('D1',  '内容,待解决问题');				
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '时间安排');	
		$objPHPExcel->getActiveSheet()->setCellValue('F1',  '完成情况');
		$objPHPExcel->getActiveSheet()->setCellValue('G1',  '得分');
		$objPHPExcel->getActiveSheet()->setCellValue('H1',  '说明');
		$objPHPExcel->getActiveSheet()->getStyle("B1:H1")->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A2','目标完成情况');
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);//文本加粗
		$objPHPExcel->getActiveSheet()->mergeCells("A2:B".($number[0]['num']+1));//单元格合并 这里的14应该是活的
		$objPHPExcel->getActiveSheet()->getStyle("C2:C".($number[0]['num']+1))->getFont()->setBold(true);//文本加粗
		//++++++++++++++++++++++++++++++++++++++++++++上边target+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
		$i=2;
		$array_all=$this->target();
		echo session('student_id');
		
		foreach($array_all as $key => $values)//一种任务集
		{
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $values[0]['type_content']);//这里的大小写B也是有区分的
			
			if(count($values)!=0)//否则出错  Cell coordinate can not be zero-length string  mergeCells('C3:C2')
			{
				$objPHPExcel->getActiveSheet()->mergeCells("C".$i.":C".(count($values)+$i-1));//这里慢慢的调
			}
			
			foreach($values as $key1 => $value)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['content']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['date_time1']."--".$value['date_time2']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['percent']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value['grade']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['instruction']);
				$i++;
			}	
		}
		//++++++++++++++++++++++++++++++++++++++++++++左边task+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
		$array_all=$this->task();
		//接下来就是写数据到表格里面去
		$i_stop=$i;
		foreach ($array_all as $month2 => $values) {
			
			
			$k=0;
			
			if($month2<=12&&$month2>=1)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,"第".($month2)."月");
				$objPHPExcel->getActiveSheet()->mergeCells("A".$i.":A".(count($values)+$i));//合并
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$i,  '序号');		
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$i,  '任务');				
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$i,  '内容,待解决问题');				
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, '时间安排');	
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$i,  '完成情况');
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$i,  '得分');
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$i,  '说明');
				$begin='A'.$i;
				$end='H'.$i;
				$objPHPExcel->getActiveSheet()->getStyle("$begin:$end")->getFont()->setBold(true);//文本加粗
			}
			foreach($values as $schedule =>$value)//一个月的内容
			{
				$i++;//表格是从1开始的
				$k++;
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$i,  $k);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$i,  $value['type_content']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$i,  $value['content']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$i,  $value['date_time1']."-".$value['date_time2']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$i,  $value['percent']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$i,  $value['grade']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$i,  $value['instruction']);
			}
			//此时，一个月已经完了
			$i++;
			$objPHPExcel->getActiveSheet()->mergeCells("A".$i.":R".$i);//合并
			$i++;
			
					  
		}
		
		
		//+++++++++++++++++++++++++++++++++++++右边schedule+++++++++++++++++++++++++++++++++++++++++
		
		$array_all=$this->schedule();
		//接下来就是写数据到表格里面去
		$i=$i_stop;//控制换行
		$last_month3=0;
		foreach ($array_all as $month3 => $values) {//一个月的相同项目并排显示
			$j='I';//控制换列
			$k=0;
			foreach($values as $shedule3 =>$value)//一个schedule
			{	
				//这是关键
				if($last_task_id!=$value['task_id']||$month3!=$last_month3)//换行 换列
				{
					$last_month3=$month3;//如果不这样的话，$month3!=$last_month3这个条件永远成立，一个项目只能显示一个schedule
					//$j='I';
					$i++;
					$k++;
				}
				$j='I';//每一个schedule都是从i开始算的，所以要恢复
				
				$timestamp = strtotime($value['commit_time']); 
				$week_num=$this->week($timestamp);
				for($p=1;$p<=(($week_num-1)*2);$p++)
				{
					$j=++$j;//跨周的算法实现
				}
		
				$objPHPExcel->getActiveSheet()->setCellValue(($j++).$i,  $k);
				$objPHPExcel->getActiveSheet()->setCellValue(($j++).$i,  $value['content']);
				
				
				
				$last_task_id=$value['task_id'];
			}
			$last_month3=$month3;
			$i++;
			$i++;
			if($month3<=11)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, '第一周');//这里不能写J，这个合并可以理解成向右挤压，把右面的就挤没了
				$objPHPExcel->getActiveSheet()->mergeCells("I".$i.":J".$i);//合并
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, '第二周');
				$objPHPExcel->getActiveSheet()->mergeCells("K".$i.":L".$i);//合并
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, '第三周');
				$objPHPExcel->getActiveSheet()->mergeCells("M".$i.":N".$i);//合并
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, '第四周');
				$objPHPExcel->getActiveSheet()->mergeCells("O".$i.":P".$i);//合并
				$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, '第五周');
				$objPHPExcel->getActiveSheet()->mergeCells("Q".$i.":R".$i);//合并
				$begin='I'.$i;
				$end='R'.$i;
				$objPHPExcel->getActiveSheet()->getStyle("$begin:$end")->getFont()->setBold(true);
			}
			
			
					  
		}
		/*header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");;
		header('Content-Disposition:attachment;filename=文件名称.xls');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('文件名称.xls');
		$objWriter->save('php://output');*/
		
		
		$objWriteHTML->save('php://output');
	}
	public function target()
	{
		
		//$time=date("Y");//这个用来限制查询时间
		$time=$this->time;
		
		$student_id=session('student_id');//这个用来限制查询学生编
		
		$Model=M('');
		$list=$Model->query("select task.*,type.name as type_content from task,type where task.type_id=type.id and date_format(date_time1,'%Y')<=$time and date_format(date_time2,'%Y')>=$time and task.id in ( select task_id from form where student_id=$student_id) ");//这也是限制一年当中
		$number=$Model->query("select count(*) as num from task where date_format(date_time1,'%Y')<=$time and date_format(date_time2,'%Y')>=$time and task.id in ( select task_id from form where student_id=$student_id)");
		//var_dump($number);//query返回的一定是二维数组
		$array_all=array();
		for($i=1;$i<=7;$i++)//这里的7 应该弄成活的
		{
			$array_all[$i]=array();
		}//建立二维数组一定要这样建立
		foreach($list as $key => $value)
		{
			array_push($array_all[$value['type_id']],$value);
		}
		
		return $array_all;
		//var_dump($array_all);
		//echo "+++++++++++++++++++++++++++++";
		vendor("PHPExcel.PHPExcel");//如果这里提示类不存在，肯定是你文件夹名字不对。
		$objPHPExcel = new \PHPExcel();//这里要注意‘\’ 要有这个。因为版本是3.1.2了。
		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);//设置保存版本格式	
		$objWriteHTML = new \PHPExcel_Writer_HTML($objPHPExcel);
		$objPHPExcel->getActiveSheet()->getStyle('A1:H'.($number[0]['num']+1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A1:H'.($number[0]['num']+1))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A1:H'.($number[0]['num']+1))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN); //边框
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		
		
		$objPHPExcel->getActiveSheet()->setCellValue('B1',  '序号');		
		$objPHPExcel->getActiveSheet()->setCellValue('C1',  '任务');				
		$objPHPExcel->getActiveSheet()->setCellValue('D1',  '内容,待解决问题');				
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '时间安排');	
		$objPHPExcel->getActiveSheet()->setCellValue('F1',  '完成情况');
		$objPHPExcel->getActiveSheet()->setCellValue('G1',  '得分');
		$objPHPExcel->getActiveSheet()->setCellValue('H1',  '说明');
		$objPHPExcel->getActiveSheet()->getStyle("B1:H1")->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A2','目标完成情况');
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);//文本加粗
		$objPHPExcel->getActiveSheet()->mergeCells("A2:B".($number[0]['num']+1));//单元格合并 这里的14应该是活的
		$objPHPExcel->getActiveSheet()->getStyle("C2:C".($number[0]['num']+1))->getFont()->setBold(true);//文本加粗
		$i=2;
		foreach($array_all as $key => $values)//一种任务集
		{
			
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $values[0]['type_content']);//这里的大小写B也是有区分的
			
			if(count($values)!=0)
			{
				$objPHPExcel->getActiveSheet()->mergeCells("C".$i.":C".(count($values)+$i-1));//这里慢慢的调
			}
			foreach($values as $key1 => $value)
			{
				
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $value['content']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $value['date_time1']."--".$value['date_time2']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $value['percent']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $value['grade']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $value['instruction']);
				$i++; 
			}	
			
		}
		
		/*header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");;
		header('Content-Disposition:attachment;filename=文件名称.xls');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('target.xls');
		$objWriter->save('php://output');*/
		
		$objWriteHTML->save('php://output');
	}
	public function schedule()//【重重之重】每个月必须提交一次，以后还得做这个功能的限制
	{
		//$time=date("Y");//这个用来限制查询时间
		$time=$this->time;
		$student_id=session('student_id');//这个用来限制查询学生编
	
		$Model=M('');
		//这里就应该where 固定一个人所做的全部任务号（可以在前面查出来） 把commit_time也规定在那一年 order by task_id 这就能够对应前面
		$list=$Model->query("select * from schedule where date_format(commit_time,'%Y')=$time and task_id in (select id from task where  date_format(date_time1,'%Y')<=$time and date_format(date_time2,'%Y')>=$time and task.id in ( select task_id from form where student_id=$student_id)) order by task_id,commit_time");
		/*select * from schedule where date_format(commit_time,'%Y')=$time order by task_id,commit_time  这个是数据库中的全部schedule*/
		/*select id from task where  date_format(date_time1,'%Y')<=$time and date_format(date_time2,'%Y')>=$time and task.id in ( select task_id from form where student_id=$student_id)	  这个是上面target中的符合要求的筛选后的task  把这个插入到上面那句中*/
		$array_all=array();
		for($i=1;$i<=12;$i++)
		{
			$array[$i]=array();
		}
		foreach($list as $key => $values)
		{
			$timestamp = strtotime($values['commit_time']); 
			$month=idate('m',$timestamp);
			
			array_push($array[$month],$values);
		}
		for($i=1;$i<13;$i++)
		{
			array_push($array_all,$array[$i-1]);
		}
		array_push($array_all,$array[12]);//单独把12放进去，算法的完整性
		//var_dump($array_all);
		return $array_all;
		
		
		//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		vendor("PHPExcel.PHPExcel");//如果这里提示类不存在，肯定是你文件夹名字不对。
		$objPHPExcel = new \PHPExcel();//这里要注意‘\’ 要有这个。因为版本是3.1.2了。
		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);//设置保存版本格式
		
		$objWriteHTML = new \PHPExcel_Writer_HTML($objPHPExcel);
		$objPHPExcel->getActiveSheet()->getStyle('A1:K50')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN); 
		/*$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);*/
		
		//接下来就是写数据到表格里面去
		$i=1;//控制换行
		$last_month3=0;
		foreach ($array_all as $month3 => $values) {//一个月的相同项目并排显示
			$j='H';//控制换列
			$k=0;
			foreach($values as $shedule3 =>$value)//一个schedule
			{	
				//这是关键
				if($last_task_id!=$value['task_id']||$month3!=$last_month3)//换行 换列
				{
					$last_month3=$month3;//如果不这样的话，$month3!=$last_month3这个条件永远成立，一个项目只能显示一个schedule
					$j='A';
					$i++;
					$k++;
				}
				
				//默认的是换列，不换行	
				$objPHPExcel->getActiveSheet()->setCellValue(($j++).$i,  $k);
				//$objPHPExcel->getActiveSheet()->setCellValue(($j++).$i,  $value['task_id']);
				$objPHPExcel->getActiveSheet()->setCellValue(($j++).$i,  $value['content']);
				//$objPHPExcel->getActiveSheet()->setCellValue(($j++).$i,  $value['commit_time']);
				
				
				
				$last_task_id=$value['task_id'];
			}
			$last_month3=$month3;
			$i++;
			if($month3<=11)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, '第'.($month3+1).'月');
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, '第一周');
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, '第二周');
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, '第三周');
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, '第四周');
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, '第五周');
			}
			
			
					  
		}
		$objWriteHTML->save('php://output');
	}
	public function task()
	{
		
		//$time=date("Y");//这个用来限制查询时间
		$time=$this->time;
		
		$student_id=session('student_id');//这个用来限制查询学生编
	

		$Model=M('');
		//这里就应该where 固定一个人所做的全部任务号（可以在前面查出来） 把commit_time也规定在那一年 order by task_id 这就能够对应前面
		/*$list=$Model->query("select task.*,type.name as type_content from task,type where task.type_id=type.id and date_format(date_time1,'%Y')<='2016' and date_format(date_time2,'%Y')>='2016' order by id ;");	*/	
		$list=$Model->query("select task.*,type.name as type_content from task,type where task.type_id=type.id and date_format(date_time1,'%Y')<=$time and date_format(date_time2,'%Y')>=$time and task.id in ( select task_id from form where student_id=$student_id) order by task.id");
		//echo $Model->getLastSql();
		$array_all=array();
		for($i=1;$i<=12;$i++)
		{
			$array[$i]=array();
		}
		foreach($list as $key => $values)
		{
			$timestamp1 = strtotime($values['date_time1']); 
		 	$month1=idate('m',$timestamp1);
			$year1=idate('Y',$timestamp1);
			
			$timestamp2 = strtotime($values['date_time2']); 
		    $month2=idate('m',$timestamp2);
			$year2=idate('Y',$timestamp2);
			
			if($year1 < $time && $year2 > $time)
			{
				for($i=1;$i<=12;$i++)
				{
					array_push($array[$i],$values);
				}
			}
			else if($year1 < $time && $year2==$time )
			{
				for($i=1;$i<=$month2;$i++)
				{
					array_push($array[$i],$values);
				}
			}
			else if($year1==$time && $year2 > $time)
			{
				for($i=$month1;$i<=12;$i++)
				{
					array_push($array[$i],$values);
				}
			}
			else{				
				for($i=$month1;$i<=$month2;$i++)
				{
					array_push($array[$i],$values);
				}	
			}
		}
		for($i=1;$i<=12;$i++)
		{
			array_push($array_all,$array[$i-1]);
		}
		array_push($array_all,$array[12]);
		
		return $array_all;
		//最终结果就是一个三维数组，每个月的task可以通过下表月份直接取出，每月的task以二维数组的方式存放
		//var_dump($array_all);
		//注意：task表里的date_time1 和date_time2不用了，都不是自己填的，time1等于commit_time time2等于schedule中的commit_time做个触发器
		
		//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
		vendor("PHPExcel.PHPExcel");//如果这里提示类不存在，肯定是你文件夹名字不对。
		$objPHPExcel = new \PHPExcel();//这里要注意‘\’ 要有这个。因为版本是3.1.2了。
		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);//设置保存版本格式
		
		$objWriteHTML = new \PHPExcel_Writer_HTML($objPHPExcel);
		$objPHPExcel->getActiveSheet()->getStyle('A1:I50')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		
		//接下来就是写数据到表格里面去
		$i=1;
		foreach ($array_all as $month2 => $values) {
			$k=0;
			foreach($values as $schedule =>$value)
			{
				$i++;//表格是从1开始的
				$k++;
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$i,  $k);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$i,  $value['type_id']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$i,  $value['content']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$i,  $value['date_time1']."-".$value['date_time2']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$i,  $value['percent']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$i,  $value['grade']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$i,  $value['instruction']);
			}
			$i++;
			$i++;
			if($month2<=11)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,"第".($month2+1)."月");
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$i,  '序号');
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$i,  '任务');
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$i,  '内容,待解决问题');
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, '时间安排');
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$i,  '完成情况');
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$i,  '得分');
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$i,  '说明');
			}
					  
		}
		$objWriteHTML->save('php://output');
		
	}
	public function combine()
	{
		vendor("PHPExcel.PHPExcel");//如果这里提示类不存在，肯定是你文件夹名字不对。
		$objPHPExcel = new \PHPExcel();//这里要注意‘\’ 要有这个。因为版本是3.1.2了。
		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);//设置保存版本格式
		
		$objWriteHTML = new \PHPExcel_Writer_HTML($objPHPExcel);
		$objPHPExcel->getActiveSheet()->getStyle('A1:R60')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//文本居中
		//$sheet->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//在tp3.2中不能有\
		$objPHPExcel->getActiveSheet()->getStyle('A1:R60')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('A1:R60')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中 
		/*$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25); */
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30); 
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(30);
		$array_all=$this->task();
		//接下来就是写数据到表格里面去
		$i=1;
		foreach ($array_all as $month2 => $values) {
			
			
			$k=0;
			
			if($month2<=12&&$month2>=1)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,"第".($month2)."月");
				$objPHPExcel->getActiveSheet()->mergeCells("A".$i.":A".(count($values)+$i));//合并
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$i,  '序号');		
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$i,  '任务');				
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$i,  '内容,待解决问题');				
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, '时间安排');	
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$i,  '完成情况');
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$i,  '得分');
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$i,  '说明');
				$begin='A'.$i;
				$end='H'.$i;
				$objPHPExcel->getActiveSheet()->getStyle("$begin:$end")->getFont()->setBold(true);//文本加粗
			}
			foreach($values as $schedule =>$value)//一个月的内容
			{
				$i++;//表格是从1开始的
				$k++;
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$i,  $k);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$i,  $value['type_content']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$i,  $value['content']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$i,  $value['date_time1']."-".$value['date_time2']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$i,  $value['percent']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$i,  $value['grade']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$i,  $value['instruction']);
			}
			//此时，一个月已经完了
			$i++;
			$objPHPExcel->getActiveSheet()->mergeCells("A".$i.":R".$i);//合并
			$i++;
			
					  
		}
		
		
		//+++++++++++++++++++++++++++++++++++++右边部分+++++++++++++++++++++++++++++++++++++++++
		
		$array_all=$this->schedule();
		//接下来就是写数据到表格里面去
		$i=1;//控制换行
		$last_month3=0;
		foreach ($array_all as $month3 => $values) {//一个月的相同项目并排显示
			$j='I';//控制换列
			$k=0;
			foreach($values as $shedule3 =>$value)//一个schedule
			{	
				//这是关键
				if($last_task_id!=$value['task_id']||$month3!=$last_month3)//换行 换列
				{
					$last_month3=$month3;//如果不这样的话，$month3!=$last_month3这个条件永远成立，一个项目只能显示一个schedule
					$j='I';
					$i++;
					$k++;
				}
				
				//默认的是换列，不换行	
				$objPHPExcel->getActiveSheet()->setCellValue(($j++).$i,  $k);
				//$objPHPExcel->getActiveSheet()->setCellValue(($j++).$i,  $value['task_id']);
				$objPHPExcel->getActiveSheet()->setCellValue(($j++).$i,  $value['content']);
				//$objPHPExcel->getActiveSheet()->getColumnDimension(($j++).$i)->setWidth(25);
				//$objPHPExcel->getActiveSheet()->setCellValue(($j++).$i,  $value['commit_time']);
				
				
				
				$last_task_id=$value['task_id'];
			}
			$last_month3=$month3;
			$i++;
			$i++;
			if($month3<=11)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, '第一周');//这里不能写J，这个合并可以理解成向右挤压，把右面的就挤没了
				$objPHPExcel->getActiveSheet()->mergeCells("I".$i.":J".$i);//合并
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, '第二周');
				$objPHPExcel->getActiveSheet()->mergeCells("K".$i.":L".$i);//合并
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, '第三周');
				$objPHPExcel->getActiveSheet()->mergeCells("M".$i.":N".$i);//合并
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, '第四周');
				$objPHPExcel->getActiveSheet()->mergeCells("O".$i.":P".$i);//合并
				$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, '第五周');
				$objPHPExcel->getActiveSheet()->mergeCells("Q".$i.":R".$i);//合并
				$begin='I'.$i;
				$end='R'.$i;
				$objPHPExcel->getActiveSheet()->getStyle("$begin:$end")->getFont()->setBold(true);
			}
			
			
					  
		}
		/*header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");;
		header('Content-Disposition:attachment;filename=文件名称.xls');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('文件名称.xls');
		$objWriter->save('php://output');*/
		
		
		$objWriteHTML->save('php://output');
	}
	
	public function week($cu_time)
	{
	    $week = date("w",$cu_time);
		
		if ($week != 6) {
			echo "can't submit";
			//exit();
		}else{
			$year = date("Y",$cu_time);
			$mouth = date("m",$cu_time);
			$day = date("d",$cu_time);
	
			$d=mktime(0, 0, 0, $mouth, 1, $year);//这个月的第一天，是全年的第几周
			$week1 = date("w",$d);
			$start = NULL;
			if ($week1 == 6) {
				$start = 1;
			}elseif ($week1 == 0) {
				$start = 7;
			}else{
				$start = 1 + (6 - $week1);
			}
			$differ = (($day - $start) / 7) + 1;
			//echo $differ."week";
			return $differ;
		}
	}
	
}