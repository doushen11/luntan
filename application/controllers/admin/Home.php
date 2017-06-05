<?php
	//会员后台控制器
	
	//author:recson
	
	//time:2016-6-11 9:00
	
	//QQ:1439294242
	
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("admin/mains_model","apps");
		$this->load->model("admin/home_model","home");
	}
	
	public function index()
	{
// 		$data['rs'] = $this->apps->A();
// 		$data['total_data']['shop'] = $this->home->getShopData();
// 		$data['total_data']['order'] = $this->home->getOrderData();
// 		$data['total_data']['goods'] = $this->home->getGoodsData();
// 		$data['total_data']['users'] = $this->home->getUsersData();
// 		$data['last_join_shop'] = $this->home->getLastJoinShop();
		$this->load->view("admin/index.php",'');
	}
	
	
	
}