<?php

class Circle extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("admin/circle/circle_model");
        $this->load->model("admin/mains_model","apps");
    }

    /**
     * 展示所有的朋友圈
     */
    public function index()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('circle_circle_index');
        $get_params = $this->input->get();
        
        $array['current_order'] = '';
        // 排序的字段
        $array['orderby']['thumbs_up_count'] = 'desc';
        $array['orderby']['comment_count'] = 'desc';
        
        $array['params']['nickname'] = '';
        $array['params']['page'] = '';
        // 拼接where 语句
        $str = '';
        $str_arr = array();
        if(isset($get_params['nickname']) && $get_params['nickname'] !='') {
            $array['params']['nickname'] = $get_params['nickname'];
            array_push($str_arr, 'nickname="'.$get_params['nickname'].'"');
        }
        if (sizeof($str_arr) > 0) {
            $str = 'where ' . implode(' and ', $str_arr) . ' and is_disabled='.NOT_DISABLED;
        }
        
        // 排序
        if(!empty($get_params['thumbs_up_count'])) {
            if($get_params['thumbs_up_count'] == 'desc') {
                $array['orderby']['thumbs_up_count'] = 'asc';
            } else if($get_params['thumbs_up_count'] == 'asc') {
                $array['orderby']['thumbs_up_count'] = 'desc';
            }
            $str = $str . ' order by thumbs_up_count ' . $get_params['thumbs_up_count'];
            $array['current_order'] = 'thumbs_up_count';
        }
        if(!empty($get_params['comment_count'])) {
            if($get_params['comment_count'] == 'desc') {
                $array['orderby']['comment_count'] = 'asc';
            } else if($get_params['comment_count'] == 'asc') {
                $array['orderby']['comment_count'] = 'desc';
            }
            $str = $str . ' order by comment_count ' . $get_params['comment_count'];
            $array['current_order'] = 'comment_count';
        }
        
        if(empty($get_params['page'])) {
            $array['pageindex'] = 1;
        } else {
            $array['pageindex'] = $get_params['page'];
        }
        $page_num = '20';//每页的数据
        $offset = ($array['pageindex']-1) * $page_num;
        $data= $this->circle_model->index($page_num,$offset, $str);
        
        $array['pagecount']=$data['total_nums']; //这里得到从数据库中的总条数
        $array['pageall']=ceil($data['total_nums'] / $page_num);
        $array['query'] = $data['query'];
        $array['total_circles'] = $data['total_circles'];
        
        $array['url'] = 'admin/circle/circle/index/';
        $this->load->view("admin/circle/circle/index.php",$array);
    }

    /**
     * 查看某个朋友圈的详情
     */
    public function circle_info()
    {
        $array = array();
        $array['rs'] =$this->apps->checkAuth('circle_circle_circle_info');
        $circle_id = $this->uri->segment(5);
        $array['circle'] = $this->circle_model->circle_info(array('circle_id' => $circle_id));
        $this->load->view("admin/circle/circle/circle_info.php", $array);
    }

    /**
     * 删除某个朋友圈
     */
    public function delete()
    {
        $array = array();
        $array['rs'] =$this->apps->checkAuth('circle_circle_delete');
        $circle_id = $this->input->post('circle_id');
        if ($this->db->delete('user_circle', 'id='.$circle_id)) {
            Util::success(10000, '成功', '');
        } else {
            Util::error(30000, '失败', '');
        }
    }
    
}