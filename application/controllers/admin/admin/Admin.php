<?php

class Admin extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
        $this->load->model("admin/admin/admin_model");
        $this->load->model("admin/mains_model","apps");
    }
    
    /**
     * 展示所有的管理员
     */
    public function index()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('admin_admin_index');
        $data= $this->admin_model->index();
        $array['query'] = $data['query'];
        $this->load->view("admin/admin/admin/index.php",$array);
    }
    /**
     * 添加管理员
     */
    public function add()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('admin_admin_add', true);

        if ($this->input->is_ajax_request()) {
            $param = $this->input->post();
            $this->admin_model->add($param);
        } else {
            $array['roles']=$this->admin_model->get_roles();
            $this->load->view("admin/admin/admin/add.php", $array);
        }
    }
    
    /**
     * 删除管理员
     */
    public function delete()
    {
        $data['rs']=$this->apps->checkAuth('admin_admin_delete', true);
        $admin_id = $this->input->get('admin_id');
        $this->admin_model->delete($admin_id);
    }
    
    /**
     * 编辑管理员
     */
    public function edit()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('admin_admin_edit', true);
        
        if ($this->input->is_ajax_request()) {
            //修改角色信息
            $param = $this->input->post();
            $this->admin_model->edit($param);
        } else {
            $admin_id = $this->uri->segment(5);
            $array['query'] = $this->admin_model->get_admin($admin_id);
            $array['roles']=$this->admin_model->get_roles();
            $this->load->view("admin/admin/admin/edit.php", $array);
        }
    }
    
    /**
     * 添加角色
     */
    public function role_add()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('admin_admin_role_add', true);
        
        if ($this->input->is_ajax_request()) {
            //修改角色信息
            $param = $this->input->post();
            $this->admin_model->role_add($param);
        } else {
            // 获得所有权限列表
            $array["all_permissions"]=$this->admin_model->get_all_permissions();
            $this->load->view("admin/admin/admin/role_add.php", $array);
        }
    }
    
    /**
     * 删除角色
     */
    public function role_delete()
    {
        $data['rs']=$this->apps->checkAuth('admin_admin_role_delete', true);
        $role_id = $this->input->get('role_id');
        echo $this->admin_model->role_delete($role_id);
    }
    
    /**
     * 展示所有的角色
     */
    public function role_index()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('admin_admin_role_index');
        $data= $this->admin_model->role_index();
        $array['query'] = $data['query'];
        $this->load->view("admin/admin/admin/role_index.php",$array);
    }
    
    /**
     * 查看角色权限
     */
    public function role_edit()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('admin_admin_role_edit');
        $role_id = $this->uri->segment(5);
        // 获得对应角色的权限
        $array["role_permissions"]=$this->admin_model->get_permissions($role_id);
        // 获得所有权限列表
        $array["all_permissions"]=$this->admin_model->get_all_permissions();
        $this->load->view("admin/admin/admin/role_edit.php", $array);
    }
    
    /**
     * 修改权限
     */
    public function role_update()
    {
        //角色修改
        $data['rs']=$this->apps->checkAuth('admin_admin_role_update', true);
        $role_id=intval($this->uri->segment(5));
        //修改角色信息
        $param = $this->input->post();
        $this->admin_model->role_update($param, $role_id);
    }
}