<?php
    class Qun extends CI_Controller {
        //创建群
        public function create()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!='') {
                $this->load->model("qun/qun_model");
                $this->qun_model->create($array['token']);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }
        
        // 修改群的名字
        public function update_group_name()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            &&isset($array['group_id']) && $array['group_id']!=''
            &&isset($array['group_name']) && $array['group_name']!='') {
                $this->load->model("qun/qun_model");
                $this->qun_model->update_group_name($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }
        
        // 添加群成员
        public function add_member()
        {
            $array = $this->input->post();
            LogUtil::info('---------------------------------'.json_encode($array),__METHOD__);
            if(isset($array['token']) && $array['token']!=''
            &&isset($array['group_id']) && $array['group_id']!=''
            &&isset($array['user_ids']) && $array['user_ids']!='') {
                $this->load->model("qun/qun_model");
                $this->qun_model->add_member($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }
        
        // 获取自己的群列表
        public function get_group_list()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!='') {
                $this->load->model("qun/qun_model");
                $this->qun_model->get_group_list($array['token']);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }
        
        // 根据数据库的群id获取单个群信息
        public function get_group()
        {
            $array = $this->input->get();
            if(isset($array['group_id']) && $array['group_id']!=''
            &&isset($array['token']) && $array['token']!='') {
                $this->load->model("qun/qun_model");
                $group = $this->qun_model->get_group($array['group_id'], $array['token']);
                if(!empty($group)) {
                    Util::success(10000, '成功', array('group'=>$group));
                } else {
                    Util::error(40000, '没有该群', '');
                }
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 根据环信的群id获取单个群信息
        public function get_group_by_huanxingroupid()
        {
            $array = $this->input->get();
            if(isset($array['huanxin_group_id']) && $array['huanxin_group_id']!=''
            &&isset($array['token']) && $array['token']!='') {
                $this->load->model("qun/qun_model");
                $group = $this->qun_model->get_group_by_huanxingroupid($array['huanxin_group_id'], $array['token']);
                if(!empty($group)) {
                    Util::success(10000, '成功', array('group'=>$group));
                } else {
                    Util::error(40000, '没有该群', '');
                }
            } else {
                Util::error(30000, '参数有误', '');
            }
        }
        
        // 根据自己服务器的群id获取群成员
        public function get_members()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!=''
                &&isset($array['group_id']) && $array['group_id']!='') {
                $this->load->model("qun/qun_model");
                $this->qun_model->get_members($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }
        

        // 根据环信服务器的群id获取群成员
        public function get_members_by_huanxingroupid()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!=''
            &&isset($array['huanxin_group_id']) && $array['huanxin_group_id']!='') {
                $this->load->model("qun/qun_model");
                $this->qun_model->get_members($array, 'huanxin_group');
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 删除群成员
        public function remove_member()
        {
            $array = $this->input->post();
            LogUtil::info('---------------------------------'.json_encode($array),__METHOD__);
            if(isset($array['token']) && $array['token']!=''
            &&isset($array['group_id']) && $array['group_id']!=''
            &&isset($array['user_ids']) && $array['user_ids']!='') {
                $this->load->model("qun/qun_model");
                $this->qun_model->remove_member($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 删除并退出群
        public function remove_group()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            &&isset($array['group_id']) && $array['group_id']!='') {
                $this->load->model("qun/qun_model");
                $this->qun_model->remove_group($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 删除并退出群
        public function pin()
        {
            $this->load->helper('img_pinjie');
            $imgPinjie = new ImgPinjie();
            $imgPinjie->getImg('');
        }
    }