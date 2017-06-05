<?php

class Qun extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("admin/qun/qun_model");
        $this->load->model("admin/mains_model","apps");
    }

    /**
     * 展示所有的群
     */
    public function index()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('qun_qun_index');
        $get_params = $this->input->get();
        
        $array['params']['from_date'] = '';
        $array['params']['end_date'] = '';
        $array['params']['group_name'] = '';
        $array['params']['owner_name'] = '';
        $array['params']['owner_mobile'] = '';
        $array['params']['page'] = '';
        // 拼接where 语句
        $str = '';
        $str_arr = array();
        if(!empty($get_params['from_date']) && !empty($get_params['end_date'])) {
            $array['params']['from_date'] = $get_params['from_date'];
            $array['params']['end_date'] = $get_params['end_date'];
            array_push($str_arr, 'create_time>="'.$get_params['from_date'].' 00:00:00" and create_time<="'.$get_params['end_date'].' 23:59:59"');
        }
        if(isset($get_params['group_name']) && $get_params['group_name'] !='') {
            $array['params']['group_name'] = $get_params['group_name'];
            array_push($str_arr, 'group_name="'.$get_params['group_name'].'"');
        }
        if(isset($get_params['owner_name']) && $get_params['owner_name'] !='') {
            $array['params']['owner_name'] = $get_params['owner_name'];
            array_push($str_arr, 'nickname="'.$get_params['owner_name'].'"');
        }
        if(isset($get_params['owner_mobile']) && $get_params['owner_mobile'] !='') {
            $array['params']['owner_mobile'] = $get_params['owner_mobile'];
            array_push($str_arr, 'mobile="'.$get_params['owner_mobile'].'"');
        }
        if (sizeof($str_arr) > 0) {
            $str = 'where ' . implode(' and ', $str_arr);
        }
        
        if(empty($get_params['page'])) {
            $array['pageindex'] = 1;
        } else {
            $array['pageindex'] = $get_params['page'];
        }
        $page_num = '20';//每页的数据
        $offset = ($array['pageindex']-1) * $page_num;
        $data= $this->qun_model->index($page_num,$offset, $str);
        
        $array['pagecount']=$data['total_nums']; //这里得到从数据库中的总条数
        $array['pageall']=ceil($data['total_nums'] / $page_num);
        $array['query'] = $data['query'];
        $array['total_quns'] = $data['total_quns'];
        
        $array['url'] = 'admin/qun/qun/index/';
        $this->load->view("admin/qun/qun/index.php",$array);
    }

    /**
     * 根据群id查看群成员
     */
    public function members()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('qun_qun_members');
        $get_params = $this->input->get();
        $group_id = $this->uri->segment(5);
        
        $array['params']['nickname'] = '';
        $array['params']['mobile'] = '';
        $array['params']['page'] = '';
        // 拼接where 语句
        $str = '';
        $str_arr = array();
        if(!empty($group_id)) {
            array_push($str_arr, 'group_id='.$group_id.'');
        }
        if(isset($get_params['nickname']) && $get_params['nickname'] !='') {
            $array['params']['nickname'] = $get_params['nickname'];
            array_push($str_arr, 'nickname="'.$get_params['nickname'].'"');
        }
        if(isset($get_params['mobile']) && $get_params['mobile'] !='') {
            $array['params']['mobile'] = $get_params['mobile'];
            array_push($str_arr, 'mobile="'.$get_params['mobile'].'"');
        }
        if (sizeof($str_arr) > 0) {
            $str = 'where ' . implode(' and ', $str_arr);
        }
    
        if(empty($get_params['page'])) {
            $array['pageindex'] = 1;
        } else {
            $array['pageindex'] = $get_params['page'];
        }
        $page_num = '20';//每页的数据
        $offset = ($array['pageindex']-1) * $page_num;
        $data= $this->qun_model->members($page_num,$offset, $str);
    
        $array['pagecount']=$data['total_nums']; //这里得到从数据库中的总条数
        $array['pageall']=ceil($data['total_nums'] / $page_num);
        $array['query'] = $data['query'];
    
        $array['url'] = 'admin/qun/qun/members/'.$group_id.'/';
        $this->load->view("admin/qun/qun/members.php",$array);
    }

    /**
     * 删除某个群
     */
    public function delete()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('qun_qun_delete', true);
        $qun_id = $this->input->post('qun_id');
        $data= $this->qun_model->delete($qun_id);
    }
}