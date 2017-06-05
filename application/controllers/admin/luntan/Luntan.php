<?php

class Luntan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("admin/luntan/luntan_model");
        $this->load->model("admin/mains_model","apps");
    }

    /**
     * 展示所有的论坛
     */
    public function index()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('luntan_luntan_index');
        $get_params = $this->input->get();
        
        $array['current_order'] = '';
        // 排序的字段
        $array['orderby']['tiezi_count_order'] = 'desc';
        $array['orderby']['comment_count_order'] = 'desc';
        $array['orderby']['today_see_count_order'] = 'desc';
        $array['orderby']['focus_count_order'] = 'desc';

        // 搜索的字段
        $array['params']['luntan_name'] = '';
        $array['params']['page'] = '';
        // 拼接where 语句
        $str = '';
        $str_arr = array();
        // 搜索
        if(isset($get_params['luntan_name']) && $get_params['luntan_name'] !='') {
            $array['params']['luntan_name'] = $get_params['luntan_name'];
            array_push($str_arr, 'luntan_name="'.$get_params['luntan_name'].'"');
        }
        if (sizeof($str_arr) > 0) {
            $str = 'where ' . implode(' and ', $str_arr);
        }
        // 排序
        if(!empty($get_params['tiezi_count_order'])) {
            if($get_params['tiezi_count_order'] == 'desc') {
                $array['orderby']['tiezi_count_order'] = 'asc';
            } else if($get_params['tiezi_count_order'] == 'asc') {
                $array['orderby']['tiezi_count_order'] = 'desc';
            }
            $str = $str . ' order by tiezi_count ' . $get_params['tiezi_count_order'];
            $array['current_order'] = 'tiezi_count';
        }
        if(!empty($get_params['comment_count_order'])) {
            if($get_params['comment_count_order'] == 'desc') {
                $array['orderby']['comment_count_order'] = 'asc';
            } else if($get_params['comment_count_order'] == 'asc') {
                $array['orderby']['comment_count_order'] = 'desc';
            }
            $str = $str . ' order by comment_count ' . $get_params['comment_count_order'];
            $array['current_order'] = 'comment_count';
        }
        if(!empty($get_params['today_see_count_order'])) {
            if($get_params['today_see_count_order'] == 'desc') {
                $array['orderby']['today_see_count_order'] = 'asc';
            } else if($get_params['today_see_count_order'] == 'asc') {
                $array['orderby']['today_see_count_order'] = 'desc';
            }
            $str = $str . ' order by today_see_count ' . $get_params['today_see_count_order'];
            $array['current_order'] = 'today_see_count';
        }
        if(!empty($get_params['focus_count_order'])) {
            if($get_params['focus_count_order'] == 'desc') {
                $array['orderby']['focus_count_order'] = 'asc';
            } else if($get_params['focus_count_order'] == 'asc') {
                $array['orderby']['focus_count_order'] = 'desc';
            }
            $str = $str . ' order by focus_count ' . $get_params['focus_count_order'];
            $array['current_order'] = 'focus_count';
        }
        
        
        if(empty($get_params['page'])) {
            $array['pageindex'] = 1;
        } else {
            $array['pageindex'] = $get_params['page'];
        }
        $page_num = '20';//每页的数据
        $offset = ($array['pageindex']-1) * $page_num;
        $data= $this->luntan_model->index($page_num,$offset, $str);
        
        $array['pagecount']=$data['total_nums']; //这里得到从数据库中的总条数
        $array['pageall']=ceil($data['total_nums'] / $page_num);
        $array['query'] = $data['query'];
        $array['total_luntans'] = $data['total_luntans'];
        
        $array['url'] = 'admin/luntan/luntan/index/';
        $this->load->view("admin/luntan/luntan/index.php",$array);
    }

    /**
     * 展示某个论坛的板块
     */
    public function blocks()
    {
        $array = array();
        $array['rs'] =$this->apps->checkAuth('luntan_luntan_blocks');
        $luntan_id = $this->uri->segment(5);
        $array['blocks'] = $this->luntan_model->blocks($luntan_id);
        $this->load->view("admin/luntan/luntan/blocks.php",$array);
    }

    /**
     * 删除某个板块
     */
    public function block_delete()
    {
        $array = array();
        $array['rs'] =$this->apps->checkAuth('luntan_luntan_block_delete', true);
        $this->luntan_model->block_delete($this->input->post());
    }

    /**
     * 添加某个板块
     */
    public function block_add()
    {
        $array = array();
        $array['rs'] =$this->apps->checkAuth('luntan_luntan_block_add', true);
        $this->luntan_model->block_add($this->input->post());
    }

    /**
     * 查看帖子
     */
    public function tiezi()
    {
        $array = array();
        $array['rs']=$this->apps->checkAuth('luntan_luntan_tiezi');
        $get_params = $this->input->get();
        
        $array['current_order'] = '';
        // 排序的字段
        $array['orderby']['comment_count'] = 'desc';
        $array['orderby']['see_count'] = 'desc';
        
        // 搜索的字段
        $array['params']['author_name'] = '';
        $array['params']['luntan_name'] = '';
        $array['params']['title'] = '';
        $array['params']['is_essence'] = '';
        // 拼接where 语句
        $str = '';
        $str_arr = array();
        // 搜索
        if(isset($get_params['author_name']) && $get_params['author_name'] !='') {
            $array['params']['author_name'] = $get_params['author_name'];
            array_push($str_arr, 'nickname="'.$get_params['author_name'].'"');
        }
        if(isset($get_params['luntan_name']) && $get_params['luntan_name'] !='') {
            $array['params']['luntan_name'] = $get_params['luntan_name'];
            array_push($str_arr, 'luntan_name="'.$get_params['luntan_name'].'"');
        }
        if(isset($get_params['title']) && $get_params['title'] !='') {
            $array['params']['title'] = $get_params['title'];
            array_push($str_arr, 'title="'.$get_params['title'].'"');
        }
        if(isset($get_params['is_essence']) && $get_params['is_essence'] !='') {
            $array['params']['is_essence'] = $get_params['is_essence'];
            array_push($str_arr, 'is_essence="'.$get_params['is_essence'].'"');
        }
        if (sizeof($str_arr) > 0) {
            $str = 'where ' . implode(' and ', $str_arr);
        }
        
        if(!empty($get_params['comment_count'])) {
            if($get_params['comment_count'] == 'desc') {
                $array['orderby']['comment_count'] = 'asc';
            } else if($get_params['comment_count'] == 'asc') {
                $array['orderby']['comment_count'] = 'desc';
            }
            $str = $str . ' order by tiezi.comment_count ' . $get_params['comment_count'];
            $array['current_order'] = 'comment_count';
        }
        if(!empty($get_params['see_count'])) {
            if($get_params['see_count'] == 'desc') {
                $array['orderby']['see_count'] = 'asc';
            } else if($get_params['see_count'] == 'asc') {
                $array['orderby']['see_count'] = 'desc';
            }
            $str = $str . ' order by see_count ' . $get_params['see_count'];
            $array['current_order'] = 'see_count';
        }
        
        if(empty($get_params['page'])) {
            $array['pageindex'] = 1;
        } else {
            $array['pageindex'] = $get_params['page'];
        }
        $page_num = '20';//每页的数据
        $offset = ($array['pageindex']-1) * $page_num;
        $data= $this->luntan_model->tiezi($page_num,$offset, $str);
        
        $array['pagecount']=$data['total_nums']; //这里得到从数据库中的总条数
        $array['pageall']=ceil($data['total_nums'] / $page_num);
        $array['query'] = $data['query'];
        $array['total_tiezis'] = $data['total_tiezis'];
        
        $array['url'] = 'admin/luntan/luntan/tiezi/';
        $this->load->view("admin/luntan/luntan/tiezi.php",$array);
    }

    /**
     * 查看某个帖子的详情
     */
    public function tiezi_info()
    {
        $array = array();
        $array['rs'] =$this->apps->checkAuth('luntan_luntan_tiezi_info');
        $tiezi_id = $this->uri->segment(5);
        $this->load->model('user/forum_model');
        $array['tiezi'] = $this->forum_model->tiezi_info(array('tiezi_id' => $tiezi_id), true);
        $this->load->view("admin/luntan/luntan/tiezi_info.php",$array);
    }

    /**
     * 删除某个帖子
     */
    public function tiezi_delete()
    {
        $array = array();
        $array['rs'] =$this->apps->checkAuth('luntan_luntan_tiezi_delete');
        $tiezi_id = $this->input->post('tiezi_id');
        if ($this->db->delete('luntan_tiezi', 'id='.$tiezi_id)) {
            Util::success(10000, '成功', '');
        } else {
            Util::error(30000, '失败', '');
        }
    }

    /**
     * 设置精华帖
     */
    public function tiezi_essence()
    {
        $array = array();
        $array['rs'] =$this->apps->checkAuth('luntan_luntan_tiezi_essence');
        $tiezi_id = intval($this->input->post('tiezi_id'));
        $is_essence = intval($this->input->post('is_essence'));
        if ($this->db->update('luntan_tiezi', array('is_essence' => $is_essence), 'id='.$tiezi_id)) {
            Util::success(10000, '成功', '');
        } else {
            Util::error(30000, '失败', '');
        }
    }

    /**
     * 删除某个论坛
     */
    public function delete()
    {
        $array = array();
        $array['rs'] =$this->apps->checkAuth('luntan_luntan_delete', true);
        $this->luntan_model->delete($this->input->post());
    }
}