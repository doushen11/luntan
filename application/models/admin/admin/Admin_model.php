<?php
    class Admin_model extends CI_Model
    {
        /**
         * 展示所有的管理员
         */
        public function index(){
            $data = array();
            $query = $this->db->query('select zj_admin.id,zj_admin.group,zj_admin.username,zj_admin.last_time,zj_admin.last_ip,zj_admin_role.name from zj_admin left join zj_admin_role on zj_admin.group=zj_admin_role.id');
            $da=$query->result_array();
            $data['query']=$da;
            return $data;
        }
        
        /**
         * 添加管理员
         */
        public function add($param){
            if(!$this->check_exists($param['username'])) {
                $keys = mt_rand(100000,999999);
                $arr = array(
                        'username' => $param['username'],
                        'passwd' => sha1(sha1($param['passwd']).$keys),
                        'keys'=>$keys,
                        "login_time"=>date('Y-m-d h:m:s'),
                        "login_ip"=>Util::get_ip(),
                        "group"=>$param['role'],
                        "token"=>sha1(microtime()."cc").md5(date("Y-m-d").uniqid()."yy"),
                );
                if($this->db->insert('admin', $arr)) {
                    Util::success(10000, '成功', '');
                } else {
                    Util::error(30000, '登录账户已存在', '');
                }
            } else {
                Util::error(40000, '登录账户已存在', '');
            }
        }
        
        /**
         * 修改管理员
         */
        public function edit($param){
            $keys = mt_rand(100000,999999);
            $arr = array(
                    'passwd' => sha1(sha1($param['passwd']).$keys),
                    'keys'=>$keys,
                    "group"=>$param['role'],
                    "token"=>sha1(microtime()."cc").md5(date("Y-m-d").uniqid()."yy"),
            );
            if($this->db->update('admin', $arr, 'id=' . $param['admin_id'])) {
                Util::success(10000, '成功', '');
            } else {
                Util::error(30000, '修改失败', '');
            }
        }
        
        /**
         * 检测登录名是否存在
         */
        private function check_exists($username) {
            $query = $this->db->query('select * from zj_admin where username="' . $username . '"');
            if($query->num_rows() > 0) {
                return true;
            }
            return false;
        }
        
        /**
         * 展示所有的角色
         */
        public function role_index(){
            $data = array();
            $query = $this->db->query('select * from zj_admin_role');
            $da=$query->result_array();
            $data['query']=$da;
            return $data;
        }
        
        /**
         * 获取角色列表
         */
        public function get_roles()
        {
            return $this->db->query("select id,name from `zj_admin_role` order by `id` asc")->result_array();
        }
        
        /**
         * 管理角色权限
         */
        public function role_edit(){
            $data = array();
            $query = $this->db->query('select * from zj_admin_role');
            $da=$query->result_array();
            $data['query']=$da;
            return $data;
        }
        
        /**
         * 根据角色id查询对应权限
         */
        public function get_permissions($role_id){
            $query=$this->db->query("select * from `zj_admin_role` where `id`='$role_id'");
            return $query->row_array();
        }

        /**
         * 根据管理员id查询管理员
         */
        public function get_admin($admin_id){
            $query=$this->db->query("select * from `zj_admin` where `id`='$admin_id'");
            return $query->row_array();
        }
        
        /**
         * 获得所有权限列表
         */
        public function get_all_permissions(){
            return $this->db->query("select * from `zj_admin_permission` order by `id` asc");
        }
        
        // 根据name 和id 查看role name是否重复
        private function check_role_name($name, $id)
        {
            $query=$this->db->query("select `id` from `zj_admin_role` where `name`='$name' and `id`!='$id' limit 1");
            if($query->num_rows() > 0) {
                return false;
            } else {
                return true;
            }
        }
        
        
        // 根据name 查看role是否重复
        private function check_role($name)
        {
            $query=$this->db->query("select `id` from `zj_admin_role` where `name`='$name'");
            if($query->num_rows() > 0) {
                return false;
            } else {
                return true;
            }
        }
        
        /**
         * 修改权限
         */
        public function role_update($param, $role_id)
        {
            $_array=array(
                    "name"=>$param['controller_name'],
                    "controller_funcs"=>$param['func_name_value'],
            );
            if($this->check_role_name($param['controller_name'], $role_id)) {
                if($this->db->update("admin_role",$_array,array("id"=>$role_id)))
                {
                    Util::success(10000, '成功', '');
                } else {
                    Util::error(30000, '失败', '');
                }
            } else {
                Util::error(40000, '处理失败，角色名称已经被占用', '');
            }
        }
        
        /**
         * 添加角色
         */
        public function role_add($param)
        {
            $_array=array(
                    "name"=>$param['controller_name'],
                    "controller_funcs"=>$param['func_name_value'],
            );
            if($this->check_role($param['controller_name'])) {
                if($this->db->insert("admin_role",$_array))
                {
                    Util::success(10000, '成功', '');
                } else {
                    Util::error(30000, '失败', '');
                }
            } else {
                Util::error(40000, '处理失败，角色名称已经被占用', '');
            }
        }
        
        /**
         * 判断该角色下是否还有管理员
         */
        private function has_role_admins($role_id)
        {
            $num =  $this->db->query("select `id` from `zj_admin` where `group`='$role_id' limit 1")->num_rows();
            if($num > 0) {
                return true;
            } else {
                return false;
            }
        }
        
        /**
         * 删除角色
         */
        public function role_delete($role_id)
        {
            if(!$this->has_role_admins($role_id)) {
                if($this->db->query("delete from `zj_admin_role` where `id`='$role_id'")) {
                    Util::success(10000, '成功', '');
                } else {
                    Util::error(30000, '删除失败', '');
                }
            } else {
                Util::error(40000, '该角色下存在管理员,不能删除', '');
            }
        }
        
        /**
         * 删除角色
         */
        public function delete($admin_id)
        {
            if($this->db->query("delete from `zj_admin` where `id`='$admin_id'")) {
                Util::success(10000, '成功', '');
            } else {
                Util::error(30000, '删除失败', '');
            }
        }
    }