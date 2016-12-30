<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	function _initialize() //这里使用contruct是不行的，他并不会默认调用
	{
		if(empty(session('student_id')))
		{
			$this->error('请登录！',U("Login/login"));
		}
	}
}