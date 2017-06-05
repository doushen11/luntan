<?php
    //后台的全局控制器

    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Mains_model extends CI_Model
    {

        private $dbprefix="";
        
        function __construct()
        {
            parent::__construct();
            $this->dbprefix=$this->db->dbprefix;
            $this->load->library('encrypt');
        }
        
        public function subs($username,$passwd)
        {
            //登录程序处理
            $sql="select `id`,`passwd`,`keys`,`login_ip`,`login_time` from `zj_admin` where `username`='$username' limit 1";
            $query=$this->db->query($sql);
            if($query->num_rows()>0)
            {
                $result=$query->row_array();
                $db_passwd=$result["passwd"];
                $sub_passwd=sha1(sha1($passwd).$result["keys"]);
                if($db_passwd==$sub_passwd)
                {
                    $_array=array(
                        "login_time"=>date('Y-m-d h:m:s'),
                        "login_ip"=>Util::get_ip(),
                        "last_time"=>$result["login_time"],
                        "last_ip"=>$result["login_ip"],
                        "token"=>sha1(microtime()."cc").md5(date("Y-m-d").uniqid()."yy"),
                    );
                    if($this->db->update("admin",$_array,array("id"=>$result["id"])))
                    {
                        $_login_str=$this->encrypt->encode($result["id"]."|".$result["keys"]."|".$username."|".$_array["token"]);
                        setcookie("wc_author",$_login_str,time()+3600*24*3,"/");
                        return "success";
                    }
                }
            }
            return "faild";
        }
        
        private function login_check()
        {
            //登录数据验证
            if(isset($_COOKIE["wc_author"]))
            {
                
                if($str=$this->encrypt->decode($_COOKIE["wc_author"]))
                {
                    if(substr_count($str,"|")==3)
                    {
                        $arr=explode("|",$str);    
                        $sql="select * from `zj_admin` where `id`='".trim($arr[0])."' and `keys`='".trim($arr[1])."' and `username`='".trim($arr[2])."'  and `token`='".trim($arr[3])."' limit 1";
                        $query=$this->db->query($sql);
                        setcookie("wc_author",$_COOKIE["wc_author"],time()+3600*24*3,"/");
                        return $query->row_array();
                    }
                }
                setcookie("wc_author","",time()-3600*24*3,"/");
            }    
            return "faild"; 
        }
        
        private function level_check($rs)
        {
            //验证权限
            $func="{".strtolower($this->uri->segment(3))."}";
            if($rs["group"]==0)
            {
                return "success";
            }    
            else
            {
                $query=$this->db->query("select `controller_funcs` from `zj_admin_role` where `id`='".$rs["group"]."' limit 1");
                if($query->num_rows()>0)
                {
                    $result=$query->row_array();
                    if(substr_count($result["controller_funcs"],$func)>0)
                    {
                        return "success";
                    }
                }    
                return "error";
            }
        }
        
        public function L()
        {
            //直接获取验证返回值信息
            return $this->login_check();
        }
        
        public function A($check=null)
        {
            //Ajax登录验证
            $rs=$this->login_check();
            $rs1="success";
            if($check==1){
                $rs1=$this->level_check($rs);
            }
            if(is_array($rs) && isset($rs["id"]) && intval($rs["id"])>0)
            {
                if($rs1=="success")
                {
                    return $rs;    
                }
                else
                {
                    echo "destory";exit();
                }
            }
            else
            {
                echo "losing";exit();    
            }
        }
        
        public function N($check=null)
        {
            //常规登录验证
            $rs=$this->login_check();
                        
                        if($rs=='faild'){
                            $this->logouts();
                            exit;
                        }
            $rs1="success";
            if($check==1){
                $rs1=$this->level_check($rs);
            }
            if(is_array($rs) && isset($rs["id"]) && intval($rs["id"])>0)
            {
                if($rs1=="success")
                {
                    return $rs;    
                }
                else
                {
                    $this->destroys();
                }
            }
            else
            {
                $this->logouts();    
            }
        }
        
        public function destroys()
        {
            header("location:".base_url()."index.php/admin/login/destory");
            die();
        }
        
        public function R($check=null)
        {
            //frame登录验证
            $rs=$this->login_check();
            $rs1="success";
            if($check==1){
                $rs1=$this->level_check($rs);
            }
            if(is_array($rs) && isset($rs["id"]) && intval($rs["id"])>0)
            {
                if($rs1=="success")
                {
                    return $rs;    
                }
                else
                {
                    iframes("40000","您没有操作权限!");
                }
            }
            else
            {
                iframes("20000","登录状态已经失效!");
            }    
        }
        
        public function logouts()
        {    
            //退出会员-Model操作
            setcookie("wc_author","",time()-3600*24*3,"/");    
            header("location:".http_url()."admin/login/indexs");
            die();
        }

        /**
        * 检测权限,如果无权限则跳转提示页面
        * @param $identifier 权限标识符
        * @return array
        **/
        public function checkAuth($identifier, $is_ajax = false)
        {
            $currentUserInfo = $this->L();
            if(empty($currentUserInfo) || !isset($currentUserInfo['group'])){
                $this->destroys();
                die();
            }
            $groupId = (int)$currentUserInfo['group'];
            if($groupId == '0'){
                //超级管理员不做检测直接返回
                return $currentUserInfo;
            }else{
                $result = $this->db->query("SELECT * FROM `zj_admin_role` WHERE id=$groupId")->row_array();
                if(isset($result['controller_funcs'])){
                    $nodes = explode(',',$result['controller_funcs']);
                    if(in_array('{'.$identifier.'}',$nodes)){
                        //检测成功，返回当前用户信息
                        return $currentUserInfo;
                    }else{
                        // 如果是ajax访问的话，网页没办法自动跳转，所以向前台返回json数据，用前台跳转
                        if($is_ajax) {
                            json_array2(20000, '没有权限', '');
                        } else {
                            $this->destroys();
                            die();
                        }
                    }
                }else{
                    $this->destroys();
                    die();
                }
            }
        }
        
    }