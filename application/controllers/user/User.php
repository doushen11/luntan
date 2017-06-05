<?php
    
    class User extends CI_Controller {
        //获取验证码
        public function code()
        {
            $array = $this->input->get();
            if(isset($array['mobile']) && $array['mobile']!='') {
                $this->load->model("user/user_model");
                $this->user_model->code($array['mobile']);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        //注册
        public function registe()
        {
            $array = $this->input->post();
            if(isset($array['mobile']) && $array['mobile']!=''
               &&isset($array['passwd']) && $array['passwd']!=''
               &&isset($array['code']) && $array['code']!='') {
                $this->load->model("user/user_model");
                $this->user_model->registe($array['mobile'], $array['passwd'], $array['code']);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        //用户APP登录
        public function login()
        {
            $array = $this->input->post();
            if(isset($array['mobile']) && $array['mobile']!=''
            &&isset($array['passwd']) && $array['passwd']!='') {
                $this->load->model("user/user_model");
                $this->user_model->login($array['mobile'], $array['passwd']);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }
        
        // 忘记密码
        public function reset_password()
        {
            $array = $this->input->post();
            if(isset($array['mobile']) && $array['mobile']!=''
            && isset($array['code']) && $array['code']!=''
            && isset($array['passwd']) && $array['passwd']!=''
            && isset($array['repeat_passwd']) && $array['repeat_passwd']!='') {
                $this->load->model("user/user_model");
                $this->user_model->reset_password($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }
        
        // 第三方登录，关联手机
        public function socal_account_login()
        {
            $array = $this->input->post();
            if(isset($array['mobile']) && $array['mobile']!=''
            &&isset($array['passwd']) && $array['passwd']!=''
            &&isset($array['code']) && $array['code']!=''
            &&isset($array['social_type']) && $array['social_type']!=''
            &&isset($array['social_account']) && $array['social_account']!='') {
                $this->load->model("user/user_model");
                $this->user_model->socal_account_login($array['mobile'], $array['passwd'], $array['code'], $array['social_type'], $array['social_account']);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }
        
        // 第三方登录，关联手机
        public function get_user()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!='') {
                $this->load->model("user/user_model");
                $user = $this->user_model->get_user($array['token']);
                if(!empty($user)) {
                    Util::success(10000, '成功', array('user'=>$user));
                } else {
                    Util::error(40000, '没有该用户', '');
                }
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 修改头像
        public function update_photo()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            &&isset($_FILES['photo']) && $_FILES['photo']!='') {
                $this->load->model("user/user_model");
                $this->user_model->update_photo($array['token']);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 修改昵称
        public function update_nickname()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            &&isset($array['nickname']) && $array['nickname']!='') {
                $this->load->model("user/user_model");
                $this->user_model->update_nickname($array['token'], $array['nickname']);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 修改签名
        public function update_signature()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            &&isset($array['signature']) && $array['signature']!='') {
                $this->load->model("user/user_model");
                $this->user_model->update_signature($array['token'], $array['signature']);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 保存当前位置
        public function save_location()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['longitude']) && $array['longitude']!=''
            && isset($array['latitude']) && $array['latitude']!='') {
                $this->load->model("user/user_model");
                $this->user_model->save_location($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 获得附近的人
        public function near_person()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['longitude']) && $array['longitude']!=''
            && isset($array['latitude']) && $array['latitude']!='') {
                $this->load->model("user/user_model");
                $this->user_model->near_person($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 修改密码
        public function update_password()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['old_passwd']) && $array['old_passwd']!=''
            && isset($array['passwd']) && $array['passwd']!=''
            && isset($array['repeat_passwd']) && $array['repeat_passwd']!='') {
                $this->load->model("user/user_model");
                $this->user_model->update_password($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 根据手机号查询用户
        public function search_mobile()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['mobile']) && $array['mobile']!='') {
                $this->load->model("user/user_model");
                $this->user_model->search_mobile($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 发送好友请求
        public function send_friend_apply()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['apply_user_id']) && $array['apply_user_id']!=''
            && isset($array['see_friend_circle']) && $array['see_friend_circle']!='') {
                $this->load->model("user/user_model");
                $this->user_model->send_friend_apply($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 查询跟自己相关的好友请求
        public function search_friend_apply()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!='') {
                $this->load->model("user/user_model");
                $this->user_model->search_friend_apply($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 处理好友请求
        public function deal_friend_apply()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['apply_id']) && $array['apply_id']!=''
            && isset($array['accept']) && $array['accept']!='') {
                $this->load->model("user/user_model");
                $this->user_model->deal_friend_apply($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 设置备注和描述
        public function update_comment_description()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['friend_user_id']) && $array['friend_user_id']!=''
            && isset($array['friend_comment']) && $array['friend_comment']!=''
            && isset($array['description'])) {
                $this->load->model("user/user_model");
                $this->user_model->update_comment_description($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 获得好友列表
        public function friends()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!='') {
                $this->load->model("user/user_model");
                $this->user_model->friends($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 删除好友
        public function delete_friend()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            &&isset($array['friend_id']) && $array['friend_id']!='') {
                $this->load->model("user/user_model");
                $this->user_model->delete_friend($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 添加黑名单
        public function add_blacklist()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['friend_id']) && $array['friend_id']!='') {
                $this->load->model("user/user_model");
                $this->user_model->add_blacklist($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 从黑名单中删除人
        public function delete_blacklist()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['blacklist_user_id']) && $array['blacklist_user_id']!='') {
                $this->load->model("user/user_model");
                $this->user_model->delete_blacklist($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 保存群到通讯录
        public function add_group_to_book()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['group_id']) && $array['group_id']!=''
            && isset($array['type']) && $array['type']!='') {
                $this->load->model("user/user_model");
                $this->user_model->add_group_to_book($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 通过用户id获取详细资料
        public function user_info()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['user_id']) && $array['user_id']!='') {
                $this->load->model("user/user_model");
                $this->user_model->user_info($array, 'user_id');
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 通过用户手机号获取详细资料
        public function get_user_by_mobile()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['mobile']) && $array['mobile']!='') {
                $this->load->model("user/user_model");
                $this->user_model->user_info($array, 'mobile');
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 朋友圈发布文字
        public function publish_txt()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['txt']) && $array['txt']!=''
            && isset($array['location']) && $array['location']!=''
            && isset($array['longitude']) && $array['longitude']!=''
            && isset($array['latitude']) && $array['latitude']!=''
            && isset($array['see_type']) && $array['see_type']!=''
            && isset($array['access_user_ids']) && $array['access_user_ids']!=''
            && isset($array['tip_user_ids']) && $array['tip_user_ids']!='') {
                $this->load->model("user/user_model");
                $this->user_model->publish_txt($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 朋友圈发布文字和图片
        public function publish_img()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['txt']) && $array['txt']!=''
            && isset($array['location']) && $array['location']!=''
            && isset($array['longitude']) && $array['longitude']!=''
            && isset($array['latitude']) && $array['latitude']!=''
            && isset($array['see_type']) && $array['see_type']!=''
            && isset($array['access_user_ids']) && $array['access_user_ids']!=''
            && isset($array['tip_user_ids']) && $array['tip_user_ids']!=''
            && isset($array['imgs']) && $array['imgs']!='') {
                $this->load->model("user/user_model");
                $this->user_model->publish_img($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 朋友圈发布文字和视频
        public function publish_video()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['txt']) && $array['txt']!=''
            && isset($array['location']) && $array['location']!=''
            && isset($array['longitude']) && $array['longitude']!=''
            && isset($array['latitude']) && $array['latitude']!=''
            && isset($array['see_type']) && $array['see_type']!=''
            && isset($array['access_user_ids']) && $array['access_user_ids']!=''
            && isset($array['tip_user_ids']) && $array['tip_user_ids']!=''
            && isset($_FILES['video']) && $_FILES['video']!='') {
                $this->load->model("user/user_model");
                $this->user_model->publish_video($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 获取自己的朋友圈
        public function get_circle()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['page']) && $array['page']!='') {
                $this->load->model("user/user_model");
                $this->user_model->get_circle($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

       /**
        * circle_id: 某条朋友圈的id
        * comment_id： 如果不为0，则表示该条评论评论的是别人的评论，而不是某条朋友圈
        * comment： 评论内容
        * 评论
        */
        public function circle_comment()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['circle_id']) && $array['circle_id']!=''
            && isset($array['comment_id']) && $array['comment_id']!=''
            && isset($array['comment']) && $array['comment']!='') {
                $this->load->model("user/user_model");
                $this->user_model->circle_comment($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        /**
         * 点赞
         */
        public function circle_thumbs_up()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['circle_id']) && $array['circle_id']!='') {
                $this->load->model("user/user_model");
                $this->user_model->circle_thumbs_up($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        /**
         * 收藏
         */
        public function circle_collecte()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['circle_id']) && $array['circle_id']!='') {
                $this->load->model("user/user_model");
                $this->user_model->circle_collecte($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        /**
         * 取消收藏某条朋友圈
         */
        public function circle_cancel_collection()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['circle_id']) && $array['circle_id']!='') {
                $this->load->model("user/user_model");
                $this->user_model->circle_cancel_collection($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        /**
         * 获取收藏的朋友圈
         */
        public function circle_collection()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!='') {
                $this->load->model("user/user_model");
                $this->user_model->circle_collection($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        /**
         * 删除某条朋友圈
         */
        public function circle_delete()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['circle_id']) && $array['circle_id']!='') {
                $this->load->model("user/user_model");
                $this->user_model->circle_delete($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        /**
         * 转发某条朋友圈
         */
        public function circle_forward()
        {
            $array = $this->input->post();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['circle_id']) && $array['circle_id']!='') {
                $this->load->model("user/user_model");
                $this->user_model->circle_forward($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        /**
         * 获取收藏的帖子
         */
        public function tiezi_collection()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['page']) && $array['page']!='') {
                $this->load->model("user/user_model");
                $this->user_model->tiezi_collection($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        /**
         * 个人中心，我的帖子接口
         */
        public function my_tiezi()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['page']) && $array['page']!='') {
                $this->load->model("user/user_model");
                $this->user_model->my_tiezi($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        /**
         * 个人中心，我的相册接口
         */
        public function my_album()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['page']) && $array['page']!='') {
                $this->load->model("user/user_model");
                $this->user_model->my_album($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        // 获取某条朋友圈的评论，点赞
        public function get_circle_comment_thumbs()
        {
            $array = $this->input->get();
            if(isset($array['token']) && $array['token']!=''
            && isset($array['circle_id']) && $array['circle_id']!='') {
                $this->load->model("user/user_model");
                $this->user_model->get_circle_comment_thumbs($array);
            } else {
                Util::error(30000, '参数有误', '');
            }
        }

        /**
         * 获得安装包的下载地址
         */
        public function get_download_address() {
            $url = base_url().'index.php/user/user/show_download_page';
            Util::success(10000, '成功', array('url'=>$url));
        }

        /**
         * 展示安装包的下载页面
         */
        public function show_download_page() {
            $array['query'] = $this->db->query('select * from zj_package_version where phone="iphone" limit 1')->row_array();
            $this->load->model("user/user_model");
            $this->load->view("user/user/download_page.php", $array);
        }
    }