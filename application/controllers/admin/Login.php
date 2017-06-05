<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Login extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			$this->load->model("admin/mains_model","apps");
			$this->load->model("admin/admins_model","admins");
		}
		
		public function index()
		{
			//后台首页登录
			$this->load->view("admin/login.php");
		}
		
		public function subs()
		{
			//后台登录程序处理
			echo $this->apps->subs($this->input->post("username"),$this->input->post("passwd"));
		}
		
		public function destory()
		{
			//无权操作页面
			$this->load->view("errors/levels/index.html");
		}
		
		public function logout()
		{
			//退出会员功能
			setcookie("rs_author","",time()-3600*24*7,"/");
			Util::success(10000, '退出成功', '');
// 			header("location:".http_url()."admin/login/index");
		}
		
	}