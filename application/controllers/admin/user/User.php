<?php

class User extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("admin/user/user_model");
        $this->load->model("admin/mains_model","apps");
    }

    /**
     * 展示所有的用户
     */
    public function index()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('user_user_index');
        $get_params = $this->input->get();
        
        $array['params']['mobile'] = '';
        $array['params']['nickname'] = '';
        $array['params']['page'] = '';
        // 拼接where 语句
        $str = '';
        $str_arr = array();
        if(isset($get_params['mobile']) && $get_params['mobile'] !='') {
            $array['params']['mobile'] = $get_params['mobile'];
            array_push($str_arr, 'mobile="'.$get_params['mobile'].'"');
        }
        if(isset($get_params['nickname']) && $get_params['nickname'] !='') {
            $array['params']['nickname'] = $get_params['nickname'];
            array_push($str_arr, 'nickname="'.$get_params['nickname'].'"');
        }
        if (sizeof($str_arr) > 0) {
            $str = 'where ' . implode(' and ', $str_arr) . ' and is_disabled='.NOT_DISABLED;
        } else {
            $str = 'where is_disabled='.NOT_DISABLED;
        }
        
        if(empty($get_params['page'])) {
            $array['pageindex'] = 1;
        } else {
            $array['pageindex'] = $get_params['page'];
        }
        $page_num = '20';//每页的数据
        $offset = ($array['pageindex']-1) * $page_num;
        $data= $this->user_model->index($page_num,$offset, $str);
        
        $array['pagecount']=$data['total_nums']; //这里得到从数据库中的总条数
        $array['pageall']=ceil($data['total_nums'] / $page_num);
        $array['query'] = $data['query'];
        $array['total_users'] = $data['total_users'];
        
        $array['url'] = 'admin/user/user/index/';
        $this->load->view("admin/user/user/index.php",$array);
    }

    /**
     * 展示禁用的用户
     */
    public function disabled()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('user_user_disabled');
        $get_params = $this->input->get();
    
        $array['params']['mobile'] = '';
        $array['params']['nickname'] = '';
        $array['params']['page'] = '';
        // 拼接where 语句
        $str = '';
        $str_arr = array();
        if(isset($get_params['mobile']) && $get_params['mobile'] !='') {
            $array['params']['mobile'] = $get_params['mobile'];
            array_push($str_arr, 'mobile="'.$get_params['mobile'].'"');
        }
        if(isset($get_params['nickname']) && $get_params['nickname'] !='') {
            $array['params']['nickname'] = $get_params['nickname'];
            array_push($str_arr, 'nickname="'.$get_params['nickname'].'"');
        }
        if (sizeof($str_arr) > 0) {
            $str = 'where ' . implode(' and ', $str_arr) . ' and is_disabled='.IS_DISABLED;
        } else {
            $str = 'where is_disabled='.IS_DISABLED;
        }
    
        if(empty($get_params['page'])) {
            $array['pageindex'] = 1;
        } else {
            $array['pageindex'] = $get_params['page'];
        }
        $page_num = '20';//每页的数据
        $offset = ($array['pageindex']-1) * $page_num;
        $data= $this->user_model->index($page_num,$offset, $str);
    
        $array['pagecount']=$data['total_nums']; //这里得到从数据库中的总条数
        $array['pageall']=ceil($data['total_nums'] / $page_num);
        $array['query'] = $data['query'];
        $array['total_users'] = $data['total_users'];
    
        $array['url'] = 'admin/user/user/index/';
        $this->load->view("admin/user/user/disabled.php",$array);
    }

    /**
     * 展示某个用户的好友
     */
    public function friends()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('user_user_friends');
        $get_params = $this->input->get();
        $user_id = $this->uri->segment(5);
    
        $array['params']['mobile'] = '';
        $array['params']['nickname'] = '';
        $array['params']['page'] = '';
        // 拼接where 语句
        $str = '';
        $str_arr = array();
        if(!empty($user_id)) {
            array_push($str_arr, 'user_id='.$user_id);
        }
        if(isset($get_params['mobile']) && $get_params['mobile'] !='') {
            $array['params']['mobile'] = $get_params['mobile'];
            array_push($str_arr, 'friend_mobile="'.$get_params['mobile'].'"');
        }
        if(isset($get_params['nickname']) && $get_params['nickname'] !='') {
            $array['params']['nickname'] = $get_params['nickname'];
            array_push($str_arr, 'friend_nickname="'.$get_params['nickname'].'"');
        }
        if (sizeof($str_arr) > 0) {
            $str = 'where ' . implode(' and ', $str_arr) . ' and is_deleted='.NOT_DELETED;
        } else {
            $str = 'where is_deleted='.NOT_DELETED;
        }
    
        if(empty($get_params['page'])) {
            $array['pageindex'] = 1;
        } else {
            $array['pageindex'] = $get_params['page'];
        }
        $page_num = '20';//每页的数据
        $offset = ($array['pageindex']-1) * $page_num;
        $data= $this->user_model->friends($page_num,$offset, $str, $user_id);
    
        $array['pagecount']=$data['total_nums']; //这里得到从数据库中的总条数
        $array['pageall']=ceil($data['total_nums'] / $page_num);
        $array['query'] = $data['query'];
        $array['total_users'] = $data['total_users'];
    
        $array['url'] = 'admin/user/user/friends/';
        $this->load->view("admin/user/user/friends.php",$array);
    }

    /**
     * 展示某个用户的黑名单
     */
    public function blacklist()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('user_user_blacklist');
        $get_params = $this->input->get();
        $user_id = $this->uri->segment(5);
    
        $array['params']['mobile'] = '';
        $array['params']['nickname'] = '';
        $array['params']['page'] = '';
        // 拼接where 语句
        $str = '';
        $str_arr = array();
        if(!empty($user_id)) {
            array_push($str_arr, 'user_id='.$user_id);
        }
        if(isset($get_params['mobile']) && $get_params['mobile'] !='') {
            $array['params']['mobile'] = $get_params['mobile'];
            array_push($str_arr, 'blacklist_mobile="'.$get_params['mobile'].'"');
        }
        if(isset($get_params['nickname']) && $get_params['nickname'] !='') {
            $array['params']['nickname'] = $get_params['nickname'];
            array_push($str_arr, 'blacklist_nickname="'.$get_params['nickname'].'"');
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
        $data= $this->user_model->blacklist($page_num,$offset, $str, $user_id);
    
        $array['pagecount']=$data['total_nums']; //这里得到从数据库中的总条数
        $array['pageall']=ceil($data['total_nums'] / $page_num);
        $array['query'] = $data['query'];
        $array['total_users'] = $data['total_users'];
    
        $array['url'] = 'admin/user/user/blacklist/';
        $this->load->view("admin/user/user/blacklist.php",$array);
    }

    /**
     * 禁用某个用户
     */
    public function disable()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('user_user_disable', true);
        $user_id = $this->input->post('user_id');
        $data= $this->user_model->disable($user_id);
    }

    /**
     * 启用某个用户
     */
    public function enable()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('user_user_enable', true);
        $user_id = $this->input->post('user_id');
        $data= $this->user_model->enable($user_id);
    }

    /**
     * 投诉列表
     */
    public function complaint_list()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('user_user_complaint_list');
        $get_params = $this->input->get();
        
        // 被投诉人的昵称和电话
        $array['params']['complainted_nickname'] = '';
        $array['params']['complainted_mobile'] = '';
        // 投诉人的昵称和电话
        $array['params']['complaint_nickname'] = '';
        $array['params']['complaint_mobile'] = '';
        $array['params']['page'] = '';
        // 拼接where 语句
        $str = '';
        $str_arr = array();
        if(!empty($get_params['complainted_nickname'])) {
            $array['params']['complainted_nickname'] = $get_params['complainted_nickname'];
            array_push($str_arr, 'u2.nickname="'.$get_params['complainted_nickname'].'"');
        }
        if(!empty($get_params['complainted_mobile'])) {
            $array['params']['complainted_mobile'] = $get_params['complainted_mobile'];
            array_push($str_arr, 'u2.mobile="'.$get_params['complainted_mobile'].'"');
        }
        if(!empty($get_params['complaint_nickname'])) {
            $array['params']['complaint_nickname'] = $get_params['complaint_nickname'];
            array_push($str_arr, 'u1.nickname="'.$get_params['complaint_nickname'].'"');
        }
        if(!empty($get_params['complaint_mobile'])) {
            $array['params']['complaint_mobile'] = $get_params['complaint_mobile'];
            array_push($str_arr, 'u1.mobile="'.$get_params['complaint_mobile'].'"');
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
        $data= $this->user_model->complaint_list($page_num,$offset, $str);
        
        $array['pagecount']=$data['total_nums']; //这里得到从数据库中的总条数
        $array['pageall']=ceil($data['total_nums'] / $page_num);
        $array['query'] = $data['query'];
        $array['total_complaints'] = $data['total_complaints'];
        
        $array['url'] = 'admin/user/user/complaint_list/';
        $this->load->view("admin/user/user/complaint_list.php",$array);
    }

    /**
     * 删除某条投诉
     */
    public function complaint_delete()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('user_user_complaint_delete', true);
        $complaint_id = $this->input->post('complaint_id');
        if($this->db->delete('user_luntan_complaint', 'id='.$complaint_id)) {
            Util::success(10000, '成功', '');
        } else {
            Util::error(30000, '失败', '');
        }
    }
}