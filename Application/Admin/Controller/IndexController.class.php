<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
	 function _initialize() //这里使用contruct是不行的，他并不会默认调用
	 {
		if(empty(session('teacher_id')))
		{
			$this->error('请登录！',U("Login/login"));
		}
   	}
    public function index(){
        
    }
}