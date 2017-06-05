<?php
class Forum extends CI_Controller {
    /**
     * 获取所有的论坛/未登录状态
     */
    public function home_list()
    {
        $this->load->model("user/forum_model");
        $this->forum_model->home_list();
    }

    /**
     * 获取所有的论坛/登录状态
     */
    public function forum_list()
    {
        $array = $this->input->get();
        if(isset($array['token']) && $array['token']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->forum_list($array['token']);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 关注某个论坛
     */
    public function forum_focus()
    {
        $array = $this->input->post();
        if(isset($array['token']) && $array['token']!=''
        &&isset($array['luntan_id']) && $array['luntan_id']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->forum_focus($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 取消关注某个论坛
     */
    public function forum_cancel_focus()
    {
        $array = $this->input->post();
        if(isset($array['token']) && $array['token']!=''
        &&isset($array['luntan_id']) && $array['luntan_id']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->forum_cancel_focus($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 记录某个论坛的当日浏览量
     */
    public function forum_today_see_count()
    {
        $array = $this->input->post();
        if(isset($array['luntan_id']) && $array['luntan_id']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->forum_today_see_count($array['luntan_id']);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 获取单个论坛详情
     */
    public function forum_info()
    {
        $array = $this->input->get();
        if(isset($array['luntan_id']) && $array['luntan_id']!='') {
            $this->load->model("user/forum_model");
            $luntan = $this->forum_model->get_luntan_by_id($array['luntan_id']);
            Util::success(10000, '成功', array('luntan'=>$luntan));
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 获取某个论坛的模块
     */
    public function forum_blocks()
    {
        $array = $this->input->get();
        if(isset($array['luntan_id']) && $array['luntan_id']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->forum_blocks($array['luntan_id']);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    // 帖子发布文字
    public function publish_txt()
    {
        $array = $this->input->post();
        if(isset($array['token']) && $array['token']!=''
        && isset($array['luntan_id']) && $array['luntan_id']!=''
        && isset($array['luntan_block_id']) && $array['luntan_block_id']!=''
        && isset($array['title']) && $array['title']!=''
        && isset($array['content']) && $array['content']!=''
        && isset($array['location_province']) && $array['location_province']!=''
        && isset($array['location_city']) && $array['location_city']!=''
        && isset($array['location_county']) && $array['location_county']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->publish_txt($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    // 帖子发布文字和图片
    public function publish_img()
    {
        $array = $this->input->post();
        if(isset($array['token']) && $array['token']!=''
        && isset($array['luntan_id']) && $array['luntan_id']!=''
        && isset($array['luntan_block_id']) && $array['luntan_block_id']!=''
        && isset($array['title']) && $array['title']!=''
        && isset($array['content']) && $array['content']!=''
        && isset($array['location_province']) && $array['location_province']!=''
        && isset($array['location_city']) && $array['location_city']!=''
        && isset($array['location_county']) && $array['location_county']!=''
        && isset($array['imgs']) && $array['imgs']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->publish_img($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    // 帖子发布文字和视频
    public function publish_video()
    {
        $array = $this->input->post();
        if(isset($array['token']) && $array['token']!=''
        && isset($array['luntan_id']) && $array['luntan_id']!=''
        && isset($array['luntan_block_id']) && $array['luntan_block_id']!=''
        && isset($array['title']) && $array['title']!=''
        && isset($array['content']) && $array['content']!=''
        && isset($array['location_province']) && $array['location_province']!=''
        && isset($array['location_city']) && $array['location_city']!=''
        && isset($array['location_county']) && $array['location_county']!=''
        && isset($_FILES['video']) && $_FILES['video']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->publish_video($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 评论
     */
    public function tiezi_comment()
    {
        $array = $this->input->post();
        if(isset($array['token']) && $array['token']!=''
        && isset($array['tiezi_id']) && $array['tiezi_id']!=''
        && isset($array['comment_id']) && $array['comment_id']!=''
        && isset($array['comment']) && $array['comment']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->tiezi_comment($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 最新回复帖子
     */
    public function new_comment_tiezi()
    {
        $array = $this->input->get();
        if(isset($array['luntan_id']) && $array['luntan_id']!=''
        && isset($array['page']) && $array['page']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->new_comment_tiezi($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 最新帖子
     */
    public function new_tiezi()
    {
        $array = $this->input->get();
        if(isset($array['luntan_id']) && $array['luntan_id']!=''
        && isset($array['page']) && $array['page']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->new_tiezi($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 精华帖子
     */
    public function essence_tiezi()
    {
        $array = $this->input->get();
        if(isset($array['luntan_id']) && $array['luntan_id']!=''
        && isset($array['page']) && $array['page']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->essence_tiezi($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 外部搜索接口,根据关键字搜索论坛和帖子
     */
    public function external_search()
    {
        $array = $this->input->get();
        if(isset($array['keyword']) && $array['keyword']!=''
        && isset($array['page']) && $array['page']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->external_search($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 内部搜索接口,根据关键字搜索论坛内部的帖子
     */
    public function internal_search()
    {
        $array = $this->input->get();
        if(isset($array['luntan_id']) && $array['luntan_id']!=''
        && isset($array['keyword']) && $array['keyword']!=''
        && isset($array['page']) && $array['page']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->internal_search($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 获取某个作者的详细信息,包括他发表的帖子信息
     */
    public function author_info()
    {
        $array = $this->input->get();
        if(isset($array['user_id']) && $array['user_id']!=''
        && isset($array['page']) && $array['page']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->author_info($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 获取某个帖子的详细信息
     */
    public function tiezi_info()
    {
        $array = $this->input->get();
        if(isset($array['tiezi_id']) && $array['tiezi_id']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->tiezi_info($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 收藏某个帖子
     */
    public function tiezi_collecte()
    {
        $array = $this->input->post();
        if(isset($array['token']) && $array['token']!=''
        &&isset($array['tiezi_id']) && $array['tiezi_id']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->tiezi_collecte($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }

    /**
     * 取消收藏某个帖子
     */
    public function tiezi_cancel_collection()
    {
        $array = $this->input->post();
        if(isset($array['token']) && $array['token']!=''
        &&isset($array['tiezi_id']) && $array['tiezi_id']!='') {
            $this->load->model("user/forum_model");
            $this->forum_model->tiezi_cancel_collection($array);
        } else {
            Util::error(30000, '参数有误', '');
        }
    }
}