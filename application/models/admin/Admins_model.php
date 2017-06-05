<?php
	//后台的管理员控制器

	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Admins_model extends CI_Model
	{

		private $dbprefix="";
		
		function __construct()
		{
			parent::__construct();
			$this->dbprefix=$this->db->dbprefix;
		}
		
		//查询方法
		public function get_admins()
		{
			//获取管理员的MODEL方法
			return $this->db->query("select `a`.*,`p`.`name` from `zj_admin` as `a` left join `zj_admin_role` as `p` on `a`.`group`=`p`.`id` order by `a`.`id` desc");
		}		
		
		public function get_roles()
		{
			//获取管理权限的MODEL方法
			return $this->db->query("select * from `zj_admin_role` order by `id` asc");	
		}
		
		public function get_roles_lists()
		{
			//获取管理权限详细权限MODEL方法
			return $this->db->query("select * from `zj_admin_permission` order by `id` asc");	
		}
		
		public function get_admins_counts($c)
		{
			//根据id查询管理员行数
			$id=intval($this->uri->segment($c));
			$query=$this->db->query("select * from `zj_admin` where `id`='$id'");
			if($query->num_rows()>0)
			{
				return $query->row_array();
			}	
			else
			{
				return "";	
			}
		}
		
		public function get_roles_counts($c)
		{
			//根据id查询角色行数
			$id=intval($this->uri->segment($c));
			$query=$this->db->query("select * from `zj_admin_role` where `id`='$id'");
			if($query->num_rows()>0)
			{
				return $query->row_array();
			}	
			else
			{
				return "";	
			}
		}
		
		private function get_admin_name_counts($name,$id=null)
		{
			//获取对应的管理员名称行数
			if($id=="")
			{
				$query=$this->db->query("select `id` from `zj_admin` where `username`='$name' limit 1");
			}
			else
			{
				$query=$this->db->query("select `id` from `zj_admin` where `username`='$name' and `id`!='$id' limit 1");	
			}
			return $query->row_array();				
		}
		
		private function get_role_name_counts($name,$id=null)
		{
			//获取对应的角色名称行数
			if($id=="")
			{
				$query=$this->db->query("select `id` from `zj_admin_role` where `name`='$name' limit 1");
			}
			else
			{
				$query=$this->db->query("select `id` from `zj_admin_role` where `name`='$name' and `id`!='$id' limit 1");	
			}
			return $query->row_array();
			
		}
		
		private function get_role_admins($id)
		{
			//根据角色去查询对应的管理员行数
			return $this->db->query("select `id` from `zj_admin` where `group`='$id' limit 1")->num_rows();		
		}
                
                
		
		//插入/更新方法
		public function admin_insert()
		{
			//添加管理员
			$username=trim($this->input->post("username"));
			$passwd=trim($this->input->post("passwd"));
			$group=trim($this->input->post("group"));
			$counts=$this->get_admin_name_counts($username);
			if($counts>0)
			{
				return "use";
			}
			else
			{
				$key=mt_rand(10000000,99999999);
				$_array=array(
					"username"=>$username,
					"passwd"=>sha1(sha1($passwd).$key),
					"keys"=>$key,
					"login_time"=>time(),
					"login_ip"=>ip2long(get_ip()),
					"last_time"=>time(),
					"last_ip"=>ip2long(get_ip()),
					"group"=>$group,
					"token"=>"",
				);	
				if($this->db->insert("admin",$_array))
				{
					return "success";
				}
				return "";
			}				
		}
		
		public function admin_update()
		{
			//修改管理员
			$id=intval($this->uri->segment(4));
			$passwd=trim($this->input->post("passwd"));
			$group=trim($this->input->post("group"));
			$key=mt_rand(10000000,99999999);
			$_array=array(
				"group"=>$group,
			);
			if(strlen($passwd)>=6 && strlen($passwd)<=16)
			{
				$_array["keys"]=$key;
				$_array["passwd"]=sha1(sha1($passwd).$key);
			}	
			if($this->db->update("admin",$_array,array("id"=>$id)))
			{
				return "success";
			}
			return "";				
		}
		
		public function admin_del()
		{
			//删除管理员
			$id=intval($this->input->post("id"));
			if($this->db->query("delete from `zj_admin` where `id`='$id'"))
			{
				return "success";
			}
			return "";		
		}
		
		public function role_insert()
		{
			//添加角色信息
			$controller_name=$this->input->post("controller_name");
			$func_name=$this->input->post("func_name_value");
			$counts=$this->get_role_name_counts($controller_name);
			if($counts>0)
			{
				return "use";
			}
			else
			{
				$_array=array(
					"name"=>$controller_name,
					"controller_funcs"=>$func_name,
				);	
				if($this->db->insert("admins_permission",$_array))
				{
					return "success";
				}
				return "";
			}
			
		}
		
		public function role_update()
		{
			//修改角色信息
			$controller_name=$this->input->post("controller_name");
			$func_name=$this->input->post("func_name_value");
			$id=intval($this->uri->segment(4));
			$counts=$this->get_role_name_counts($controller_name,$id);	
			if($counts>0)
			{
				return "use";
			}
			else
			{
				$_array=array(
					"name"=>$controller_name,
					"controller_funcs"=>$func_name,
				);	
				if($this->db->update("admins_permission",$_array,array("id"=>$id)))
				{
					return "success";
				}
				return "";				
			}
		}
		
		public function role_del()
		{
			//删除角色
			$id=intval($this->input->post("id"));
			$admins=$this->get_role_admins($id);
			if($admins>0)
			{
				return "alls";
			}
			else
			{
				if($this->db->query("delete from `zj_admin_role` where `id`='$id'"))
				{
					return "success";
				}
				return "";	
			}
		}
		
		
	}