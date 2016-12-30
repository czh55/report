<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bootstrap Admin</title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" type="text/css" href="/report/public/lib/bootstrap/css/bootstrap.css">
    
    <link rel="stylesheet" type="text/css" href="/report/public/stylesheets/theme.css">
    <link rel="stylesheet" href="/report/public/lib/font-awesome/css/font-awesome.css">

    <script src="/report/public/lib/jquery-1.7.2.min.js" type="text/javascript"></script>
	<!--日历-->
    <script type="text/javascript" src="/report/public/calendar/js/laydate.js"></script>

    <!-- Demo page code -->

    <style type="text/css">
        #line-chart {
            height:300px;
            width:800px;
            margin: 0px auto;
            margin-top: 1em;
        }
        .brand { font-family: georgia, serif; }
        .brand .first {
            color: #ccc;
            font-style: italic;
        }
        .brand .second {
            color: #fff;
            font-weight: bold;
        }
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!--> 
  <body class=""> 
  <!--<![endif]-->
    
    <div class="navbar">
        <div class="navbar-inner">
                <ul class="nav pull-right">
                    
                    <li><a href="#" class="hidden-phone visible-tablet visible-desktop" role="button">Settings</a></li>
                    <li id="fat-menu" class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-user"></i> Jack Smith
                            <i class="icon-caret-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a tabindex="-1" href="#">My Account</a></li>
                            <li class="divider"></li>
                            <li><a tabindex="-1" class="visible-phone" href="#">Settings</a></li>
                            <li class="divider visible-phone"></li>
                            <li><a tabindex="-1" href="/report/index.php/Admin/Login/login_out">Logout</a></li>
                        </ul>
                    </li>
                    
                </ul>
                <a class="brand" href="index.html"><span class="first">Your</span> <span class="second">Company</span></a>
        </div>
    </div>
    


    <div class="copyrights">Collect from <a href="http://www.cssmoban.com/"  title="网站模板">网站模板</a></div>
    <div class="sidebar-nav">
        <form class="search form-inline">
            <input type="text" placeholder="Search...">
        </form>

        <a href="#dashboard-menu" class="nav-header" data-toggle="collapse"><i class="icon-dashboard"></i></a>
        <ul id="dashboard-menu" class="nav nav-list collapse in">
            <li><a href="/report/index.php/Admin/Percent/list_all">任务进度查看</a></li>
            <li ><a href="/report/index.php/Admin/Grade/list_all">得分统计</a></li>
            <li ><a href="/report/index.php/Admin/Verifying/list_all">需审核任务</a></li> 
            <li ><a href="/report/index.php/Admin/Teacher/info">个人信息修改</a></li>        
        </ul>

       <!-- <a href="#accounts-menu " class="nav-header" data-toggle="collapse"><i class="icon-briefcase"></i>学生得分</a>
        <ul id="accounts-menu" class="nav nav-list collapse in">
            <li ><a href="/report/index.php/Admin/Form/form">小组任务</a></li>
            <li ><a href="/report/index.php/Admin/Form/own">个人任务</a></li>
        </ul>
-->
       <!-- <a href="#error-menu" class="nav-header collapsed" data-toggle="collapse"><i class="icon-exclamation-sign"></i>任务目标<i class="icon-chevron-up"></i></a>
        <ul id="error-menu" class="nav nav-list collapse in">
            <li ><a href="#">三年总规划管理</a></li>
            <li ><a href="#">本年目标管理</a></li>
            <li ><a href="#">年度目标管理</a></li>
        </ul>
        
         <a href="#error-menu" class="nav-header collapsed" data-toggle="collapse"><i class="icon-exclamation-sign"></i>个人信息管理<i class="icon-chevron-up"></i></a>
        <ul id="error-menu" class="nav nav-list collapse in">
            <li ><a href="/report/index.php/Admin/Student/info">个人信息管理</a></li>
        </ul>

        <a href="#legal-menu" class="nav-header" data-toggle="collapse"><i class="icon-legal"></i>Excel</a>
        <ul id="legal-menu" class="nav nav-list collapse in">
            <li ><a href="/report/index.php/Admin/Excel/all" target="_blank">本年度Excel</a></li>
            <li ><a href="/report/index.php/Admin/Excel/check">Execl查询</a></li>
        </ul>
-->
 
    </div>
    


    
    <div class="content">
        
        <div class="header">
            
            <h1 class="page-title">Edit User</h1>
        </div>
        
        <ul class="breadcrumb">
            <li><a href="../teacher/index.html">Home</a> <span class="divider">/</span></li>
            <li><a href="../teacher/users.html">Users</a> <span class="divider">/</span></li>
            <li class="active">User</li>
        </ul>

        <div class="container-fluid">
            <div class="row-fluid">
                
                <div class="well">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#home" data-toggle="tab">Profile</a></li>
                        <li><a href="#profile" data-toggle="tab">Password</a></li>
                    </ul>
                    
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane active in" id="home">
                            <form id="tab" method="post" action="/report/index.php/Admin/teacher/save_teacher">
                            	
                                <label>姓名</label>
                                <input type="text" value="<?php echo ($name); ?>" class="input-xlarge" name="name">
                                <label>密码</label>
                                <input type="text" value="<?php echo ($password); ?>" class="input-xlarge" name="password">
                                <input type="text" value="<?php echo ($id); ?>" class="input-xlarge hidden" name="id" >
                                <div class="btn-toolbar">
                                    <button class="btn btn-primary" type="submit"><i class="icon-save"></i> Save</button>
                                    <a href="#myModal" data-toggle="modal" class="btn">Delete</a>
                                    <div class="btn-group">
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        
                        <div class="tab-pane fade" id="profile">
                            <form id="tab2">
                            <label>New Password</label>
                            <input type="password" class="input-xlarge">
                            <div>
                                <button class="btn btn-primary">Update</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
       
                <div class="modal small hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel">Delete Confirmation</h3>
                    </div>
                    <div class="modal-body">                   
                    	<p class="error-text"><i class="icon-warning-sign modal-icon"></i>Are you sure you want to delete the user?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        <button class="btn btn-danger" data-dismiss="modal">Delete</button>
                    </div>
                </div>
       
                <footer>
                    <hr>                
                    <p class="pull-right">Collect from <a href="http://www.cssmoban.com/" title="网页模板" target="_blank">网页模板</a></p>
                    <p>&copy; 2012 <a href="#" target="_blank">Portnine</a></p>
                </footer>
                    
            </div>
        </div>
    </div>
    
    
    <script src="/report/Public/lib/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript">
        $("[rel=tooltip]").tooltip();
        $(function() {
            $('.demo-cancel-click').click(function(){return false;});
        });
    </script> 
  </body>
</html>