<?php
class Forum_model extends CI_Model{

    // 根据token获取用户
    public function get_user($token)
    {
        $user = $this->db->query('select * from zj_user where token="' . $token . '" limit 1')->row_array();
    
        if(!empty($user)) {
            if ($user['is_disabled'] == IS_DISABLED) {
                Util::error(90000, '用户被禁用', '');
            }
            return $user;
        } else {
            return '';
        }
    }

    // 根据token获取用户
    public function get_luntan_by_id($luntan_id)
    {
        $luntan = $this->db->query('select * from zj_luntan where id=' . $luntan_id . ' limit 1')->row_array();
    
        if(!empty($luntan)) {
            return $luntan;
        } else {
            Util::error(70000, '未找到该论坛', '');
        }
    }

    /**
     * 获取所有的论坛/未登录状态
     */
    public function home_list()
    {
        $luntans = $this->db->query('select * from zj_luntan')->result_array();
        Util::success(10000, '成功', array('luntans' => $luntans));
    }

    /**
     * 获取所有的论坛/登录状态
     */
    public function forum_list($token)
    {
        $user = $this->get_user($token);
        if($user != '') {
            $luntans = $this->db->query('select * from zj_luntan')->result_array();
            $focus_luntans =  $this->db->query('select * from zj_user_luntan_focus where user_id='.$user['id'])->result_array();
            foreach ($luntans as $key => $luntan) {
                $luntans[$key]['focus'] = 0;
                foreach ($focus_luntans as $focus) {
                    if($focus['luntan_id'] == $luntan['id']) {
                        $luntans[$key]['focus'] = 1;
                    }
                }
            }
            Util::success(10000, '成功', array('luntans' => $luntans));
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 关注某个论坛
     */
    public function forum_focus($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $this->db->trans_begin();
            $arr = array(
                    'user_id' => $user['id'],
                    'luntan_id' => $array['luntan_id'],
            );
            $this->db->insert('user_luntan_focus', $arr);
            $luntan = $this->get_luntan_by_id($array['luntan_id']);
            $focus_count = $luntan['focus_count'] + 1;
            $this->db->query('update zj_luntan set focus_count=' . $focus_count . ' where id=' . $array['luntan_id']);
            if($this->db->trans_status()==true) {
                $this->db->trans_commit();
                Util::success(10000, '关注成功', '');
            } else {
                $this->db->trans_rollback();
                Util::error(50000, '关注失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 取消关注某个论坛
     */
    public function forum_cancel_focus($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $this->db->trans_begin();
            $this->db->delete('user_luntan_focus', 'user_id='.$user['id'].' and luntan_id='.$array['luntan_id']);
            $luntan = $this->get_luntan_by_id($array['luntan_id']);
            $focus_count = $luntan['focus_count'] - 1;
            $this->db->query('update zj_luntan set focus_count=' . $focus_count . ' where id=' . $array['luntan_id']);
            if($this->db->trans_status()==true) {
                $this->db->trans_commit();
                Util::success(10000, '取关成功', '');
            } else {
                $this->db->trans_rollback();
                Util::error(50000, '取关失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 记录当日浏览量
     */
    public function forum_today_see_count($luntan_id)
    {
        $flag = false;
        $date = date('Y-m-d');
        $luntan = $this->get_luntan_by_id($luntan_id);
        $today_see_count = $luntan['today_see_count'] + 1;
        if($luntan['today'] == $date) {
            $flag = $this->db->query('update zj_luntan set today_see_count=' . $today_see_count . ' where id=' . $luntan_id);
        } else {
            $flag =$this->db->query('update zj_luntan set today_see_count=' . $today_see_count . ',today="'.$date.'" where id=' . $luntan_id);
        }
        if ($flag) {
            Util::success(10000, '记录成功', '');
        } else {
            Util::error(50000, '记录失败', '');
        }
    }

    /**
     * 获取某个论坛的模块
     */
    public function forum_blocks($luntan_id)
    {
        $flag = false;
        $date = date('Y-m-d');
        $blocks = $this->db->query('select * from zj_luntan_block where luntan_id=' . $luntan_id)->result_array();
        Util::success(10000, '记录成功', array('blocks' => $blocks));
    }

    // 帖子发布文本、图片、视频时的共同操作
    private function add_txt_to_tiezi($array, $user, $has_img) {
        $arr = array(
                'user_id' => $user['id'],
                'luntan_id' => $array['luntan_id'],
                'luntan_block_id' => $array['luntan_block_id'],
                'title' => $array['title'],
                'content' => $array['content'],
                'location_province' => $array['location_province'],
                'location_city' => $array['location_city'],
                'location_county' => $array['location_county'],
                'create_time' => date('Y-m-d H:i:s'),
                'has_img' => $has_img,
        );
        $this->db->insert('luntan_tiezi', $arr);
        $tiezi_id = $this->db->insert_id();
        
        // 更新论坛的帖子数
        $this->db->query('update zj_luntan set tiezi_count=tiezi_count+1 where id=' . $array['luntan_id']);
        return $tiezi_id;
    }

    // 朋友圈发布文字信息
    public function publish_txt($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $this->db->trans_begin();
            $this->add_txt_to_tiezi($array, $user, HAS_TXT);
            if($this->db->trans_status()==true) {
                $this->db->trans_commit();
                Util::success(10000, '发布成功', '');
            } else {
                $this->db->trans_rollback();
                Util::error(50000, '发布失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 朋友圈发布文字和图片信息
    public function publish_img($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $this->db->trans_begin();
            $tiezi_id = $this->add_txt_to_tiezi($array, $user, HAS_IMG);
            $this->load->helper('img_upload');
            $imgUpload = new ImgUpload();
            $data = array();
            $array['imgs'] = json_decode($array['imgs']);
            foreach ($array['imgs'] as $key=>$img) {
                $file_path = $imgUpload->upload($_FILES[$img]);
                if($file_path == 1) {
                    $this->db->trans_rollback();
                    Util::error(60000, '图片发布失败', '');
                } else {
                    $arr = array(
                            'user_id' => $user['id'],
                            'luntan_tiezi_id' => $tiezi_id,
                            'url' => $file_path,
                    );
                    array_push($data, $arr);
                }
            }
            $this->db->insert_batch('luntan_tiezi_img', $data);
    
            if($this->db->trans_status()==true) {
                $this->db->trans_commit();
                Util::success(10000, '发布成功', '');
            } else {
                $this->db->trans_rollback();
                Util::error(50000, '发布失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 朋友圈发布文字和视频信息
    public function publish_video($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $this->db->trans_begin();
            $tiezi_id = $this->add_txt_to_tiezi($array, $user, HAS_VIDEO);
            $this->load->helper('file_upload');
            $data = array();
            $file_upload = new FileUpload();
            $flag = $file_upload->upload('video');
            $path = '';
            if($flag) {
                $path = $file_upload->getPath().'/'.$file_upload->getFileName();
                $ab_path = FCPATH.$file_upload->getPath().'/'.$file_upload->getFileName();
                $thumbnail_path = 'public/upload/thumbnail/'.md5(date('Y-m-d H:i:s')).'.jpg';
                $this->load->helper('thumbnail');
                $thumbnail = new Thumbnail();
                $thumbnail->convert($ab_path, FCPATH.$thumbnail_path);
                $arr = array(
                        'user_id' => $user['id'],
                        'luntan_tiezi_id' => $tiezi_id,
                        'url' => $path,
                        'thumbnail_url' => $thumbnail_path,
                );
                $this->db->insert('luntan_tiezi_video',$arr);
            } else {
                $this->db->trans_rollback();
                Util::error(60000, '视频发布失败', '');
            }
    
            if($this->db->trans_status()==true) {
                $this->db->trans_commit();
                Util::success(10000, '发布成功', '');
            } else {
                $this->db->trans_rollback();
                Util::error(50000, '发布失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 评论
     */
    public function tiezi_comment($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $this->db->trans_begin();

            // 对帖子下面评论的评论
            if($array['comment_id'] != 0) {
                $arr1 = array(
                        'parent_id' => $array['comment_id'],
                        'comment_user_id' => $user['id'],
                        'comment_time' => date('Y-m-d H:i:s'),
                        'comment_type' => 'for_comment',
                        'content' => $array['comment'],
                );
                $this->db->insert('user_tiezi_comment',$arr1);
                $com_id = $this->db->insert_id();
            } else {// 直接对帖子的评论
                $arr = array(
                        'parent_id' => $array['tiezi_id'],
                        'comment_user_id' => $user['id'],
                        'comment_time' => date('Y-m-d H:i:s'),
                        'comment_type' => 'for_tiezi',
                        'content' => $array['comment'],
                );
                $this->db->insert('user_tiezi_comment',$arr);
                $com_id = $this->db->insert_id();
                $tiezi = $this->db->query('select * from zj_luntan_tiezi where id='.$array['tiezi_id']. ' limit 1')->row_array();
                $count = $tiezi['comment_count'] + 1;
                $date = date('Y-m-d H:i:s');
                $this->db->query('update zj_luntan_tiezi set comment_count=' . $count . ',comment_time="'.$date.'" where id=' . $array['tiezi_id']);
            }

            // 保存图片
            if(!empty($array['imgs'])) {
                $this->load->helper('img_upload');
                $imgUpload = new ImgUpload();
                $data = array();
                $array['imgs'] = json_decode($array['imgs']);
                foreach ($array['imgs'] as $key=>$img) {
                    $file_path = $imgUpload->upload($_FILES[$img]);
                    if($file_path == 1) {
                        $this->db->trans_rollback();
                        Util::error(60000, '图片发布失败', '');
                    } else {
                        $arr = array(
                                'tiezi_comment_id' => $com_id,
                                'url' => $file_path,
                        );
                        array_push($data, $arr);
                    }
                }
                $this->db->insert_batch('tiezi_comment_img', $data);
            }

            if($this->db->trans_status()==true) {
                $this->db->trans_commit();
                Util::success(10000, '评论成功', '');
            } else {
                $this->db->trans_rollback();
                Util::error(50000, '评论失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 最新回复帖子
     */
    public function new_comment_tiezi($array)
    {
        // 拼接where 语句
        $str = '';
        $str_arr = array();
        if(isset($array['luntan_block_id']) && $array['luntan_block_id'] !='') {
            array_push($str_arr, 'zj_luntan_tiezi.luntan_block_id="'.$array['luntan_block_id'].'"');
        }
        if(isset($array['location_province']) && $array['location_province'] !='') {
            array_push($str_arr, 'zj_luntan_tiezi.location_province="'.$array['location_province'].'"');
        }
        if(isset($array['location_city']) && $array['location_city'] !='') {
            array_push($str_arr, 'zj_luntan_tiezi.location_city="'.$array['location_city'].'"');
        }
        if(isset($array['location_county']) && $array['location_county'] !='') {
            array_push($str_arr, 'zj_luntan_tiezi.location_county="'.$array['location_county'].'"');
        }
        
        if (sizeof($str_arr) > 0) {
            $str = ' tiezi.luntan_id='. $array['luntan_id'] . ' and ' . implode(' and ', $str_arr);
        } else {
            $str = ' tiezi.luntan_id='. $array['luntan_id'];
        }
        
        $limit = 20;
        $offset = ($array['page']-1) * $limit;
        $tiezis = $this->db->query('select tiezi.id,tiezi.user_id,tiezi.title,tiezi.content,tiezi.comment_count,tiezi.see_count,tiezi.is_essence,tiezi.has_img,zj_luntan_block.block_name from zj_luntan_tiezi as tiezi left join zj_luntan_block on zj_luntan_block.id=tiezi.luntan_block_id where' .$str. ' order by comment_time desc limit ' . $limit . ' offset ' . $offset)->result_array();
        foreach ($tiezis as $key => $tiezi) {
            $u = $this->db->query('select * from zj_user where id=' . $tiezi['user_id'] . ' limit 1')->row_array();
            $author = array(
                    'id' => $u['id'],
                    'nickname' => $u['nickname'],
                    'photo' => $u['photo'],
            );
            $tiezis[$key]['author'] = $author;
        }
        Util::success(10000, '成功', array('tiezis' => $tiezis));
    }

    /**
     * 最新帖子
     */
    public function new_tiezi($array)
    {
        // 拼接where 语句
        $str = '';
        $str_arr = array();
        if(isset($array['luntan_block_id']) && $array['luntan_block_id'] !='') {
            array_push($str_arr, 'zj_luntan_tiezi.luntan_block_id="'.$array['luntan_block_id'].'"');
        }
        if(isset($array['location_province']) && $array['location_province'] !='') {
            array_push($str_arr, 'zj_luntan_tiezi.location_province="'.$array['location_province'].'"');
        }
        if(isset($array['location_city']) && $array['location_city'] !='') {
            array_push($str_arr, 'zj_luntan_tiezi.location_city="'.$array['location_city'].'"');
        }
        if(isset($array['location_county']) && $array['location_county'] !='') {
            array_push($str_arr, 'zj_luntan_tiezi.location_county="'.$array['location_county'].'"');
        }
        
        if (sizeof($str_arr) > 0) {
            $str = ' tiezi.luntan_id='. $array['luntan_id'] . ' and ' . implode(' and ', $str_arr);
        } else {
            $str = ' tiezi.luntan_id='. $array['luntan_id'];
        }
        
        $limit = 20;
        $offset = ($array['page']-1) * $limit;
        $tiezis = $this->db->query('select tiezi.id,tiezi.user_id,tiezi.title,tiezi.content,tiezi.comment_count,tiezi.see_count,tiezi.is_essence,tiezi.has_img,zj_luntan_block.block_name from zj_luntan_tiezi as tiezi left join zj_luntan_block on zj_luntan_block.id=tiezi.luntan_block_id where' . $str . ' order by create_time desc limit ' . $limit . ' offset ' . $offset)->result_array();
        foreach ($tiezis as $key => $tiezi) {
            $u = $this->db->query('select * from zj_user where id=' . $tiezi['user_id'] . ' limit 1')->row_array();
            $author = array(
                    'id' => $u['id'],
                    'nickname' => $u['nickname'],
                    'photo' => $u['photo'],
            );
            $tiezis[$key]['author'] = $author;
        }
        Util::success(10000, '成功', array('tiezis' => $tiezis));
    }

    /**
     * 精华帖子
     */
    public function essence_tiezi($array)
    {
        // 拼接where 语句
        $str = '';
        $str_arr = array();
        if(isset($array['luntan_block_id']) && $array['luntan_block_id'] !='') {
            array_push($str_arr, 'zj_luntan_tiezi.luntan_block_id="'.$array['luntan_block_id'].'"');
        }
        if(isset($array['location_province']) && $array['location_province'] !='') {
            array_push($str_arr, 'zj_luntan_tiezi.location_province="'.$array['location_province'].'"');
        }
        if(isset($array['location_city']) && $array['location_city'] !='') {
            array_push($str_arr, 'zj_luntan_tiezi.location_city="'.$array['location_city'].'"');
        }
        if(isset($array['location_county']) && $array['location_county'] !='') {
            array_push($str_arr, 'zj_luntan_tiezi.location_county="'.$array['location_county'].'"');
        }
        
        if (sizeof($str_arr) > 0) {
            $str = ' tiezi.luntan_id='. $array['luntan_id'] . ' and ' . implode(' and ', $str_arr);
        } else {
            $str = ' tiezi.luntan_id='. $array['luntan_id'];
        }
        
        $limit = 20;
        $offset = ($array['page']-1) * $limit;
        $tiezis = $this->db->query('select tiezi.id,tiezi.user_id,tiezi.title,tiezi.content,tiezi.comment_count,tiezi.see_count,tiezi.is_essence,tiezi.has_img,zj_luntan_block.block_name from zj_luntan_tiezi as tiezi left join zj_luntan_block on zj_luntan_block.id=tiezi.luntan_block_id where' . $str . ' and is_essence=1 order by create_time desc limit ' . $limit . ' offset ' . $offset)->result_array();
        foreach ($tiezis as $key => $tiezi) {
            $u = $this->db->query('select * from zj_user where id=' . $tiezi['user_id'] . ' limit 1')->row_array();
            $author = array(
                    'id' => $u['id'],
                    'nickname' => $u['nickname'],
                    'photo' => $u['photo'],
            );
            $tiezis[$key]['author'] = $author;
        }
        Util::success(10000, '成功', array('tiezis' => $tiezis));
    }

    /**
     * 外部搜索接口,根据关键字搜索论坛和帖子
     */
    public function external_search($array)
    {
        $limit = 20;
        $offset = ($array['page']-1) * $limit;
        $result = $this->db->query('select * from (select id,luntan_name as title,icon,tiezi_count as count1,comment_count as count2,id as is_luntan from zj_luntan union all select id,title,content,see_count,comment_count,null from zj_luntan_tiezi) as t where title like "%' . $array['keyword'] . '%" limit ' . $limit . ' offset ' . $offset)->result_array();
        // 对搜索结果进行排序, 先把论坛放前面，再把id大的帖子放前面
        $flag = array();
        foreach($result as $arr){
            $flag[]=$arr["is_luntan"];
        }
        array_multisort($flag, SORT_ASC, $result);
        $flag2 = array();
        foreach($result as $arr){
            $flag2[]=$arr["id"];
        }
        array_multisort($flag2, SORT_DESC, $result);
        
        $info = array();
        foreach ($result as $key => $item) {
            // 论坛
            if($item['is_luntan'] > 0) {
                $arr = array(
                        'is_luntan' => 1,
                        'id' => $item['id'],
                        'luntan_name' => $item['title'],
                        'icon' => $item['icon'],
                        'tiezi_count' => $item['count1'],
                        'comment_count' => $item['count2'],
                        'author' => array(),
                );
                array_push($info, $arr);
            } else { //帖子
                $tiezi = $this->db->query('select user_id from zj_luntan_tiezi where id=' . $item['id'] . ' limit 1')->row_array();
                
                $u = $this->db->query('select * from zj_user where id=' . $tiezi['user_id'] . ' limit 1')->row_array();
                $author = array(
                        'id' => $u['id'],
                        'nickname' => $u['nickname'],
                        'photo' => $u['photo'],
                );
                $arr = array(
                        'is_luntan' => 0,
                        'id' => $item['id'],
                        'title' => $item['title'],
                        'content' => $item['icon'],
                        'see_count' => $item['count1'],
                        'comment_count' => $item['count2'],
                        'author' => $author,
                );
                array_push($info, $arr);
            }
        }
        Util::success(10000, '成功', array('info' => $info));
    }

    /**
     * 内部搜索接口,根据关键字搜索论坛内部的帖子
     */
    public function internal_search($array)
    {
        $limit = 20;
        $offset = ($array['page']-1) * $limit;
        $tiezis = $this->db->query('select * from zj_luntan_tiezi where luntan_id='.$array['luntan_id'].' and title like "%' . $array['keyword'] . '%" order by create_time desc limit ' . $limit . ' offset ' . $offset)->result_array();
        foreach ($tiezis as $key => $tiezi) {
            $u = $this->db->query('select * from zj_user where id=' . $tiezi['user_id'] . ' limit 1')->row_array();
            $author = array(
                    'id' => $u['id'],
                    'nickname' => $u['nickname'],
                    'photo' => $u['photo'],
            );
            $tiezis[$key]['author'] = $author;
        }
        Util::success(10000, '成功', array('tiezis' => $tiezis));
    }

    /**
     * 获取某个作者的详细信息,包括他发表的帖子信息
     */
    public function author_info($array)
    {
        $limit = 20;
        $offset = ($array['page']-1) * $limit;
        $info = array();
        $info['user'] = $this->db->query('select id,nickname,mobile,photo,qr_path from zj_user where id=' . $array['user_id'] . ' limit 1')->row_array();
        $info['tiezis'] = $this->db->query('select * from zj_luntan_tiezi where user_id='.$array['user_id'].' order by create_time desc limit ' . $limit . ' offset ' . $offset)->result_array();
        Util::success(10000, '成功', array('info' => $info));
    }

    /**
     * 获取某个帖子的详细信息
     */
    public function tiezi_info($array, $need_return=false)
    {
        $arr = array();
        $tiezi = $this->db->query('select * from zj_luntan_tiezi where id=' . $array['tiezi_id'] . ' limit 1')->row_array();
        $arr['tiezi'] = $tiezi;
        
        $arr_comment = array();
        // 获取评论
        
        $comments = $this->db->query('select zj_user_tiezi_comment.id,zj_user_tiezi_comment.content,zj_user_tiezi_comment.comment_user_id,zj_user.nickname,zj_user.photo from zj_user_tiezi_comment left join zj_user on zj_user.id=zj_user_tiezi_comment.comment_user_id where parent_id='.$tiezi['id']. ' and comment_type="for_tiezi"')->result_array();
        foreach ($comments as $comment) {
            if (!empty($comment)) {
                $item = array(
                        'id' => $comment['id'],
                        'parent_id' => $tiezi['id'],
                        'type' => 'for_tiezi',
                        'content' => $comment['content'],
                        'comment_user_id' => $comment['comment_user_id'],
                        'comment_user_nickname' => $comment['nickname'],
                        'comment_user_photo' => $comment['photo'],
                );
                array_push($arr_comment, $item);
            
                $parent_id = $comment['id'];
                while (true) {
                    $co = $this->db->query('select zj_user_tiezi_comment.id,zj_user_tiezi_comment.content,zj_user_tiezi_comment.comment_user_id,zj_user.nickname,zj_user.photo from zj_user_tiezi_comment left join zj_user on zj_user.id=zj_user_tiezi_comment.comment_user_id where parent_id='.$parent_id. ' and comment_type="for_comment" limit 1')->row_array();
                    if(empty($co)) {
                        break;
                    } else {
                        $item = array(
                                'id' => $co['id'],
                                'parent_id' => $parent_id,
                                'type' => 'for_comment',
                                'content' => $co['content'],
                                'comment_user_id' => $co['comment_user_id'],
                                'comment_user_nickname' => $co['nickname'],
                                'comment_user_photo' => $co['photo'],
                        );
                        $parent_id = $co['id'];
                        array_push($arr_comment, $item);
                    }
                }
            }
        }
        $arr['comments'] = $arr_comment;
        
        if($tiezi['has_img'] !=0 ) {
            if($tiezi['has_img'] == 1) { // 有图片
                $arr['imgs'] = $this->db->query('select url from zj_luntan_tiezi_img where luntan_tiezi_id=' . $tiezi['id'])->result_array();
            } elseif ($tiezi['has_img'] == 2) { // 有视频
                $arr['video'] = $this->db->query('select url,thumbnail_url from zj_luntan_tiezi_video where luntan_tiezi_id=' . $tiezi['id'])->result_array();
            }
        }
        if (!$need_return) {
            Util::success(10000, '成功', array('info' => $arr));
        } else {
            return $arr;
        }
    }

    /**
     * 收藏某个帖子
     */
    public function tiezi_collecte($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $arr = array(
                    'user_id' => $user['id'],
                    'luntan_tiezi_id' => $array['tiezi_id'],
            );
            if($this->db->insert('user_tiezi_collection',$arr)) {
                Util::success(10000, '收藏成功', '');
            } else {
                Util::error(30000, '收藏失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 取消收藏某个帖子
     */
    public function tiezi_cancel_collection($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            if($this->db->delete('user_tiezi_collection','user_id='.$user['id'].' and luntan_tiezi_id='.$array['tiezi_id'])) {
                Util::success(10000, '取消成功', '');
            } else {
                Util::error(30000, '取消失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }
}