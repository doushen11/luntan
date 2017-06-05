<?php
class User_model extends CI_Model{
    //发送验证码
    public function code($mobile,$table='`zj_inapp_code`',$table1='inapp_code')
    {
        $this->load->helper('curl');
        $curl = new Curl();
        $curl->setHeaders(array('Content-Type: application/json'));
        $duanxin = $this->config->item('duanxin');
        $da = array(
                'account' => $duanxin['account'],
                'password' => $duanxin['password'],
                'phone' => $mobile,
                'report' => 'false',
                'extend' => '253',
        );

        if(strlen($mobile)==11)
        {
            $query = $this->db->query("select `time` from ".$table." where mobile=$mobile limit 1");
            if($query->num_rows()>0)
            {
                $data = $query->row_array();
                if(time() - $data['time'] < 60 )
                {
                    Util::error(60000, '操作太频繁', '');
                }
                else
                {
                    $captcha=mt_rand(100000,999999);
                    $_array = array(
                            "code"=>$captcha,
                            "mobile"=>$mobile,
                            "time"=>time(),
                    );
                    if($this->db->update($table1,$_array)){
                        $da['msg'] = $duanxin['sign'].'您的验证码是：'.$captcha;
                        $result = $curl->post($duanxin['url'], json_encode($da));
                        LogUtil::info('duanxin result-------------'.json_encode($result));
                        $result = json_decode($result);
                        if($result->code == '0') {
                            Util::success(10000, '发送成功', '');
                        } else {
                            Util::error(40000, '发送不成功', '');
                        }
                    }
                    else
                    {
                        Util::error(40000, '发送不成功', '');
                    }
                }
            }
            else
            {
                $captcha=mt_rand(100000,999999);
                $_array = array(
                        "code"=>$captcha,
                        "mobile"=>$mobile,
                        "time"=>time(),
                );
                if($this->db->insert($table1,$_array)){
                    $da['msg'] = $duanxin['sign'].'您的验证码是：'.$captcha;
                    $result = $curl->post($duanxin['url'], json_encode($da));
                    LogUtil::info('duanxin result-------------'.json_encode($result));
                    $result = json_decode($result);
                    if($result->code == '0') {
                        Util::success(10000, '发送成功', '');
                    } else {
                        Util::error(40000, '发送不成功', '');
                    }
                }
                else
                {
                    Util::error(40000, '发送不成功', '');
                }
            }
        }
        else
        {
            Util::error(50000, '手机号格式不正确', '');
        }
    }

    //用户注册处理
    public function registe($mobile,$passwd,$code)
    {
        $q = $this->db->query("select `mobile` from `zj_user` where mobile = $mobile limit 1");
        if(!($q->num_rows()>0)){
            $query = $this->db->query("select * from `zj_inapp_code` where mobile = $mobile limit 1");
            if($query->num_rows()>0)
            {
                $data = $query->row_array();
                if($data['time']+15*60 >= time())
                {
                    if($code==$data['code'])
                    {
                        
                        $token = md5($code);
                        $_array = array(
                                "mobile"=>$mobile,
                                "key"=>mt_rand(100000,999999),
                                "nickname"=>mt_rand(100000,999999),
                                "photo" => 'public/image/timg.jpg',
                                "signature"=>'hello world',
                                "code"=>$code,
                                "token"=>$token,
                                "registe_time"=>date('Y-m-d H:i:s'),
                        );
                        $_array['passwd'] = md5($passwd.md5($_array['key']));
                        $this->db->trans_begin();
                        if($this->db->insert("user",$_array)){
                            $this->load->helper('qr');
                            $user_id = $this->db->insert_id();
                            $arr_qr = array(
                                    'user_id' => $user_id,
                                    'mobile' => $mobile,
                            );
                            $qr_path = QrUtil::generate_qrcode(json_encode($arr_qr));
                            $this->db->query('update zj_user set qr_path="' . $qr_path . '" where id=' . $user_id);
                            
                            $huanxin = new Huanxin();
                            $result = $huanxin->createUser($mobile, $mobile);
                            if(empty($result['error']) && $this->db->trans_status()==true) {
                                $this->db->trans_commit();
                                Util::success(10000, '注册成功', array('user_token' => $token));
                            } else {
                                $this->db->trans_rollback();
                                Util::error(40000, '注册失败', '');
                            }
                        }
                        else
                        {
                            Util::error(40000, '注册失败', '');
                        }
                    }
                    else
                    {
                        Util::error(50000, '验证码不正确', '');
                    }
                }
                else
                {
                    Util::error(60000, '验证码已经超时', '');
                }
            }else
            {
                Util::error(80000, '验证码不存在', '');
            }
        }
        else
        {
            Util::error(70000, '该手机号已被注册', '');
        }
    }

    //登录处理
    public function login($mobile,$passwd)
    {
        $query=$this->db->query("select `id`,`passwd`,`key`,`nickname`,`photo`,`is_disabled`,`qr_path` from `zj_user` where mobile = $mobile");
        if($query->num_rows()>0)
        {
            $user = $query->row_array();
            if($user['is_disabled'] == IS_DISABLED) {
                Util::error(90000, '用户被禁用', '');
            }
            
            if(md5($passwd.md5($user['key']))==$user['passwd'])
            {
                $token=sha1(date("YmdHis").microtime()).md5(mt_rand(10000000,99999999));
                $login_time = date('Y-m-d H:i:s');
                $login_ip = Util::get_ip();
                if($this->db->query('update zj_user set token="' . $token . '", login_time="' . $login_time . '", login_ip="'.$login_ip.'" where id=' . $user["id"]))
                {
                    $array = array(
                            'id'=>$user['id'],
                            'mobile'=>$mobile,
                            'token'=>$token,
                            'photo'=>$user['photo'],
                            'nickname'=>$user['nickname'],
                            'qr_path'=>$user['qr_path'],
                    );
                    Util::success(10000, '登录成功', $array);
                }
                else
                {
                    Util::error(60000, '更新失败', '');
                }
            }
            else
            {
                Util::error(40000, '账号密码不匹配', '');
            }
        }
        else
        {
            Util::error(50000, '该手机号未注册', '');
        }
    }
    
    // 忘记密码
    public function reset_password($array)
    {
        if($array['passwd']!=$array['repeat_passwd']) {
            Util::error(40000, '两次密码输入不一致', '');
        }

        $user_query = $this->db->query('select * from zj_user where mobile="' . $array['mobile'] . '" limit 1');

        if($user_query->num_rows() > 0) {
            unset($array['repeat_passwd']);
            $array['key'] = mt_rand(100000,999999);
            $array['passwd'] = md5($array['passwd'].md5($array['key']));
            $array['token'] = md5(date('Y-m-d H:i:s'));
            $this->db->update('user',$array, 'mobile=' . $array['mobile']);
            Util::success(10000, '修改成功', '');
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }
    
    // 第三方登录，关联手机
    public function socal_account_login($mobile,$passwd,$code,$social_type, $social_account)
    {
        $q = $this->db->query("select `mobile` from `zj_user` where mobile = $mobile limit 1");
        if(!($q->num_rows()>0)){
            $query = $this->db->query("select * from `zj_inapp_code` where mobile = $mobile limit 1");
            if($query->num_rows()>0)
            {
                $data = $query->row_array();
                if($data['time']+15*60 >= time())
                {
                    if($code==$data['code'])
                    {
                        $this->load->helper('qr');
                        $arr_qr = array(
                                'mobile' => $mobile,
                        );
                        $qr_path = QrUtil::generate_qrcode(json_encode($arr_qr));
    
                        $token = md5($code);
                        $_array = array(
                                "mobile"=>$mobile,
                                "key"=>mt_rand(100000,999999),
                                "nickname"=>mt_rand(100000,999999),
                                "photo" => 'public/image/timg.jpg',
                                "signature"=>'hello world',
                                "code"=>$code,
                                "token"=>$token,
                                "qr_path"=>$qr_path,
                                "registe_time"=>date('Y-m-d H:i:s'),
                                "social_type"=>$social_type,
                                "social_account"=>$social_account,
                        );
                        $_array['passwd'] = md5($passwd.md5($_array['key']));
                        
                        $this->db->trans_begin();
                        if($this->db->insert("user",$_array)){
                            $huanxin = new Huanxin();
                            $result = $huanxin->createUser($mobile, $_array['passwd']);
                            if(empty($result['error']) && $this->db->trans_status()==true) {
                                $this->db->trans_commit();
                                Util::success(10000, '注册成功', array('user_token' => $token));
                            } else {
                                $this->db->trans_rollback();
                                Util::error(40000, '注册失败', '');
                            }
                        }
                        else
                        {
                            Util::error(40000, '注册失败', '');
                        }
                    }
                    else
                    {
                        Util::error(50000, '验证码不正确', '');
                    }
                }
                else
                {
                    Util::error(60000, '验证码已经超时', '');
                }
            }else
            {
                Util::error(80000, '验证码不存在', '');
            }
        }
        else
        {
            Util::error(70000, '该手机号已被注册', '');
        }
    }
    
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

    // 修改头像
    public function update_photo($token)
    {
        $user = $this->get_user($token);
        if($user != '') {
            $this->load->helper('img_upload');
            $imgUpload = new ImgUpload();
            $file_path = $imgUpload->upload($_FILES['photo']);
            if($file_path == 1) {
                Util::error(50000, '修改头像失败', '');
            } else {
                if ($this->db->query('update zj_user set photo="' . $file_path . '" where token="' . $token . '"')) {
                    Util::success(10000, '修改头像成功', array('path' => $file_path));
                } else {
                    Util::error(60000, '修改数据库失败', '');
                }
            }
        } else {
            Util::error(40000, '没有该用户', '');
        }
    }

    // 修改昵称
    public function update_nickname($token, $nickname)
    {
        $user = $this->get_user($token);
        if($user != '') {
            $this->db->trans_begin();
            if($this->db->query('update zj_user set nickname="' . $nickname . '" where token="' . $token . '"'))
            {
                $huanxin = new Huanxin();
                $result = $huanxin->editNickname($user['mobile'], $nickname);
                if(empty($result['error']) && $this->db->trans_status()==true) {
                    $this->db->trans_commit();
                    Util::success(10000, '更新成功', '');
                } else {
                    $this->db->trans_rollback();
                    Util::error(50000, '更新失败', '');
                }
            }
            else
            {
                Util::error(50000, '更新失败', '');
            }
        } else {
            Util::error(40000, '没有该用户', '');
        }
    }
    
    // 修改签名
    public function update_signature($token, $signature)
    {
        $user = $this->get_user($token);
        if($user != '') {
            if($this->db->query('update zj_user set signature="' . $signature . '" where token="' . $token . '"'))
            {
                Util::success(10000, '更新成功', '');
            }
            else
            {
                Util::error(50000, '更新失败', '');
            }
        } else {
            Util::error(40000, '没有该用户', '');
        }
    }

    // 保存当前位置
    public function save_location($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            if($this->db->query('update zj_user set longitude="' . $array['longitude'] . '",latitude="'.$array['latitude'].'" where token="' . $array['token'] . '"')) {
                Util::success(10000, '保存成功', '');
            } else {
                Util::error(50000, '保存失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 保存当前位置
    public function near_person($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $this->load->helper('distance');
            $distance = new Distance();

            $users = $this->db->query('select id,nickname,signature,photo,latitude,longitude from zj_user where token<>"' . $array['token'].'"')->result_array();
            $arr = array();
            foreach ($users as $u) {
                if(!empty($u['longitude']) && !empty($u['latitude']) ) {
                    $d = $distance->getDistance($array['latitude'], $array['longitude'], $u['latitude'], $u['longitude']);
                    if ($d <= DISTANCE) {
                        $u['distance'] = $d;
                        array_push($arr, $u);
                    }
                }
            }
            Util::success(10000, '成功', array('users' => $arr));
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 重置密码
    public function update_password($array)
    {
        if($array['passwd']!=$array['repeat_passwd']) {
            Util::error(60000, '两次密码输入不一致', '');
        }
    
        $user = $this->get_user($array['token']);
        if($user != '') {
            $old_ps = md5($array['old_passwd'].md5($user['key']));
            if($old_ps != $user['passwd']) {
                Util::error(50000, '旧密码不对', '');
            }
            
            $new_ps = md5($array['passwd'].md5($user['key']));
            $this->db->query('update zj_user set passwd="' . $new_ps . '" where token="' . $array['token'] . '"');
            Util::success(10000, '修改成功', '');
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 根据手机号查询用户
    public function search_mobile($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $u = $this->db->query('select * from zj_user where mobile="' . $array['mobile'] . '" limit 1')->row_array();
            if(!empty($u)) {
                $friend = $this->db->query('select * from zj_friend where user_id=' . $user['id'] . ' and friend_id='.$u['id'].' and is_deleted='.NOT_DELETED.' limit 1')->row_array();
                if (!empty($friend)) {
                    $u['is_friend'] = 1;
                    Util::success(10000, '成功', array('user'=>$u));
                } else {
                    $u['is_friend'] = 0;
                    Util::success(10000, '成功', array('user'=>$u));
                }
            } else {
                Util::success(10000, '成功', array('user'=>(object)array()));
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }
    
    // 发送好友请求
    public function send_friend_apply($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $blacklist = $this->db->query('select * from zj_blacklist where user_id=' . $array['apply_user_id'] . ' and blacklist_id='.$user['id'].' limit 1')->row_array();
            if(empty($blacklist)) {
                $apply = $this->db->query('select * from zj_friend_apply where access_user_id=' . $user['id'] . ' and apply_user_id=' . $array['apply_user_id'] . ' limit 1')->row_array();
                // 以前发过请求
                if(!empty($apply)) {
                    $friend = $this->db->query('select * from zj_friend where friend_id=' . $user['id'] . ' and user_id=' . $array['apply_user_id'] . ' and is_deleted='.NOT_DELETED.' limit 1')->row_array();
                    // 如果自己是对方的好友，则不用对方通过，直接成为对方好友
                    if(!empty($friend)) {
                        $this->db->query('update zj_friend set is_deleted=' . NOT_DELETED . ' where user_id=' . $user['id'] . ' and friend_id='.$array['apply_user_id']);
                        $u = $this->db->query('select * from zj_user where id=' . $array['apply_user_id'] . ' limit 1')->row_array();
                        $huanxin = new Huanxin();
                        $flag = false;
                        $result = $huanxin->addFriend($user['mobile'], $u['mobile']);
                        if(empty($result['error'])){
                            $flag = true;
                        }
                        LogUtil::info('result----------------'.json_encode($result), __METHOD__);
                        if($flag && $this->db->trans_status()==true) {
                            $this->db->trans_commit();
                            Util::success(10000, '处理成功', array('is_friend'=>1));
                        } else {
                            $this->db->trans_rollback();
                            Util::error(70000, '处理失败', '');
                        }
                    } else {
                        $this->db->trans_begin();
                        $this->db->delete('friend_apply', 'access_user_id='.$user['id'].' and apply_user_id='.$array['apply_user_id']);
                        $arr = array(
                                'access_user_id' => $user['id'],
                                'apply_user_id' => $array['apply_user_id'],
                                'create_time' => date('Y-m-d H:i:s'),
                                'friend_comment' => !empty($array['friend_comment'])?$array['friend_comment']:'',
                                'see_friend_circle' => $array['see_friend_circle'],
                                'description' => !empty($array['description'])?$array['description']:'',
                        );
                        $this->db->insert('friend_apply', $arr);
                        
                        if(empty($result['error']) && $this->db->trans_status()==true) {
                            $this->db->trans_commit();
                            Util::success(10000, '发送成功', array('is_friend'=>0));
                        } else {
                            $this->db->trans_rollback();
                            Util::error(50000, '发送不成功', '');
                        }
                    }
                } else {
                    $arr = array(
                            'access_user_id' => $user['id'],
                            'apply_user_id' => $array['apply_user_id'],
                            'create_time' => date('Y-m-d H:i:s'),
                            'friend_comment' => !empty($array['friend_comment'])?$array['friend_comment']:'',
                            'see_friend_circle' => $array['see_friend_circle'],
                            'description' => !empty($array['description'])?$array['description']:'',
                    );
                    if($this->db->insert('friend_apply', $arr)) {
                        Util::success(10000, '发送成功', '');
                    } else {
                        Util::error(50000, '发送不成功', '');
                    }
                }
            } else {
                Util::error(60000, '已被对方加入黑名单', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 查询跟自己相关的好友请求
    public function search_friend_apply($array)
    {
        $user = $this->get_user($array['token']);
        $applys = array();
        if($user != '') {
            $items = $this->db->query('select zj_user.id,zj_user.mobile as mobile, zj_user.nickname as nickname, zj_user.photo as photo, zj_friend_apply.id as apply_id,zj_friend_apply.description as description, zj_friend_apply.friend_comment as friend_comment, zj_friend_apply.is_accepted as is_accepted from zj_friend_apply left join zj_user on zj_friend_apply.access_user_id=zj_user.id where apply_user_id=' . $user['id'])->result_array();
            foreach ($items as $item) {
                $arr = array(
                        'user_id' => $item['id'],
                        'apply_id' => $item['apply_id'],
                        'mobile' => $item['mobile'],
                        'nickname' => $item['nickname'],
                        'photo' => $item['photo'],
                        'description' => $item['description'],
                        'is_accepted' => $item['is_accepted'],
                        'friend_comment' => $item['friend_comment'],
                );
                array_push($applys, $arr);
            }
            Util::success(10000, '发送成功', $applys);
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 处理好友请求
    public function deal_friend_apply($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $apply = $this->db->query('select * from zj_friend_apply where id=' . $array['apply_id'] . ' and is_accepted='.NOT_DEAL.' limit 1')->row_array();
            if(!empty($apply)) {
                if($apply['apply_user_id'] == $user['id']) {
                    if($array['accept'] == IS_ACCEPTED) {
                        $this->db->trans_begin();
                        $this->db->query('update zj_friend_apply set is_accepted=' . IS_ACCEPTED . ' where id=' . $array['apply_id']);
                        $friend1 = $this->db->query('select * from zj_user where id=' . $apply['access_user_id'] . ' limit 1')->row_array();
                        $arr1 = array(
                                'user_id' => $user['id'],
                                'friend_id' => $apply['access_user_id'],
                                'friend_mobile' => $friend1['mobile'],
                                'friend_nickname' => $friend1['nickname'],
                                'friend_photo' => $friend1['photo'],
                                'friend_comment' => !empty($array['friend_comment'])?$array['friend_comment']:'',
                                'friend_sex' => $friend1['sex'],
                                'see_friend_circle' => isset($array['see_friend_circle'])?$array['see_friend_circle']:CAN_SEE,
                        );
                        $this->db->insert('friend', $arr1);
                        $arr2 = array(
                                'user_id' => $friend1['id'],
                                'friend_id' => $user['id'],
                                'friend_mobile' => $user['mobile'],
                                'friend_nickname' => $user['nickname'],
                                'friend_photo' => $user['photo'],
                                'friend_comment' => $apply['friend_comment'],
                                'friend_sex' => $user['sex'],
                                'see_friend_circle' => $apply['see_friend_circle'],
                        );
                        $this->db->insert('friend', $arr2);
                        $huanxin = new Huanxin();
                        $flag1 = false;
                        $flag2 = false;
                        $result1 = $huanxin->addFriend($user['mobile'], $friend1['mobile']);
                        $result2 = $huanxin->addFriend($friend1['mobile'], $user['mobile']);
                        if(empty($result1['error'])){
                            $flag1 = true;
                        }
                        if(empty($result2['error'])){
                            $flag2 = true;
                        }
                        LogUtil::info('result1----------------'.json_encode($result1), __METHOD__);
                        LogUtil::info('result2----------------'.json_encode($result2), __METHOD__);
                        if($flag1 && $flag2 && $this->db->trans_status()==true) {
                            $this->db->trans_commit();
                            Util::success(10000, '处理成功', '');
                        } else {
                            $this->db->trans_rollback();
                            Util::error(70000, '处理失败', '');
                        }
                    } elseif ($array['accept'] == NOT_ACCEPTED) {
                        $this->db->query('update zj_friend_apply set is_accepted=' . NOT_ACCEPTED . ' where id=' . $array['apply_id']);
                        Util::success(10000, '处理成功', '');
                    } else {
                        Util::error(60000, '请确定是否通过请求', '');
                    }
                } else {
                    Util::error(50000, '不存在该请求', '');
                }
            } else {
                Util::error(50000, '不存在该请求', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 设置备注和描述
    public function update_comment_description($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $flag = $this->db->query('update zj_friend set friend_comment="' . $array['friend_comment'] . '", description="'.$array['description'].'" where user_id=' . $user['id']. ' and friend_id='.$array['friend_user_id']);
            if($flag) {
                Util::success(10000, '修改成功', '');
            } else {
                Util::error(50000, '修改失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 好友列表
    public function friends($array)
    {
        $user = $this->get_user($array['token']);
        $friends = array();
        if($user != '') {
            $friends = $this->db->query('select friend_id,friend_mobile,friend_nickname,friend_photo,friend_comment from zj_friend where user_id='.$user['id'] . ' and is_deleted=' . NOT_DELETED)->result_array();
            Util::success(10000, '成功', array('friends' => $friends));
        } else {
            Util::error(40000, '未找到该对应用户', array('friends' => $friends));
        }
    }

    // 删除好友
    public function delete_friend($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $this->db->query('update zj_friend set is_deleted=' . IS_DELETED . ' where user_id=' . $user['id']. ' and friend_id='.$array['friend_id']);
            $this->db->trans_begin();
            $friend = $this->db->query('select * from zj_user where id=' . $array['friend_id'] . ' limit 1')->row_array();
            $huanxin = new Huanxin();
            $flag = false;
            $result = $huanxin->deleteFriend($user['mobile'], $friend['mobile']);
            if(empty($result['error'])){
                $flag = true;
            }
            LogUtil::info('result----------------'.json_encode($result), __METHOD__);
            if($flag && $this->db->trans_status()==true) {
                $this->db->trans_commit();
                Util::success(10000, '处理成功', '');
            } else {
                $this->db->trans_rollback();
                Util::error(50000, '处理失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 添加黑名单
    public function add_blacklist($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $this->db->trans_begin();
            $this->db->query('update zj_friend set is_deleted=' . IS_DELETED . ' where user_id=' . $user['id']. ' and friend_id='.$array['friend_id']);
//             $this->db->query('update zj_friend set is_deleted=' . IS_DELETED . ' where user_id=' . $array['friend_id']. ' and friend_id='.$user['id']);
            $u = $this->db->query('select * from zj_user where id=' . $array['friend_id'] . ' limit 1')->row_array();
            
            $arr = array(
                    'user_id' => $user['id'],
                    'blacklist_id' => $array['friend_id'],
                    'blacklist_mobile' => $u['mobile'],
                    'blacklist_nickname' => $u['nickname'],
                    'blacklist_photo' => $u['photo'],
            );
            $this->db->insert('blacklist', $arr);
            
//             $flag1 = false;
//             $flag2 = false;
//             $huanxin = new Huanxin();
//             $result1 = $huanxin->deleteFriend($user['mobile'], $u['mobile']);
//             $result2 = $huanxin->addUserForBlacklist($user['mobile'], array('usernames' => array($u['mobile'])));
//             if(empty($result1['error'])){
//                 $flag1 = true;
//             }
//             if(empty($result2['error'])){
//                 $flag2 = true;
//             }
            
            if($this->db->trans_status()==true) {
                $this->db->trans_commit();
                Util::success(10000, '处理成功', '');
            } else {
                $this->db->trans_rollback();
                Util::error(50000, '处理失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 从黑名单中删除人
    public function delete_blacklist($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $this->db->trans_begin();
            $this->db->delete('blacklist', 'user_id='.$user['id'].' and blacklist_id='.$array['blacklist_user_id']);
            $u = $this->db->query('select * from zj_user where id=' . $array['blacklist_user_id'] . ' limit 1')->row_array();
            // 恢复好友关系
            $this->db->query('update zj_friend set is_deleted=' . NOT_DELETED . ' where user_id=' . $user['id']. ' and friend_id='.$array['blacklist_user_id']);
//             $this->db->query('update zj_friend set is_deleted=' . NOT_DELETED . ' where user_id=' . $array['blacklist_user_id']. ' and friend_id='.$user['id']);

//             $flag = false;
//             $huanxin = new Huanxin();
//             $result = $huanxin->deleteUserFromBlacklist($user['mobile'], $u['mobile']);
//             if(empty($result['error'])){
//                 $flag = true;
//             }

            if($this->db->trans_status()==true) {
                $this->db->trans_commit();
                Util::success(10000, '处理成功', '');
            } else {
                $this->db->trans_rollback();
                Util::error(50000, '处理失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 保存/移除群到通讯录
    public function add_group_to_book($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $flag = false;
            if($array['type'] == 'add') {
                $flag = $this->db->query('update zj_user_group set in_address_book=' . IN_BOOK . ' where user_id=' . $user['id']. ' and group_id='.$array['group_id']);
            } elseif($array['type'] == 'remove') {
                $flag = $this->db->query('update zj_user_group set in_address_book=' . NOT_IN_BOOK . ' where user_id=' . $user['id']. ' and group_id='.$array['group_id']);
            }
            if ($flag) {
                Util::success(10000, '处理成功', '');
            } else {
                Util::error(50000, '处理失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 用户详细资料
    public function user_info($array, $type)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            if($type == 'user_id') {
                $friend = $this->db->query('select * from zj_friend where user_id=' . $user['id'] . ' and friend_id='.$array['user_id'].' and is_deleted='.NOT_DELETED.' limit 1')->row_array();
                $u = $this->db->query('select id,nickname,photo,mobile from zj_user where id=' . $array['user_id'] . ' limit 1')->row_array();
                $blacklist = $this->db->query('select * from zj_blacklist where user_id=' . $user['id'] . ' and blacklist_id='.$array['user_id'].' limit 1')->row_array();
            } else {
                $friend = $this->db->query('select * from zj_friend where user_id=' . $user['id'] . ' and friend_mobile='.$array['mobile'].' and is_deleted='.NOT_DELETED.' limit 1')->row_array();
                $u = $this->db->query('select id,nickname,photo,mobile from zj_user where mobile=' . $array['mobile'] . ' limit 1')->row_array();
                $blacklist = $this->db->query('select * from zj_blacklist where user_id=' . $user['id'] . ' and blacklist_mobile='.$array['mobile'].' limit 1')->row_array();
            }
            $is_blacklist = 0;
            if (!empty($blacklist)) {
                $is_blacklist = 1;
            }
            // 是自己的好友
            if(!empty($friend)) {
                // 允许看朋友圈
                if($friend['see_friend_circle'] == CAN_SEE) {
                    // 查找个人相册的三张照片
                    $imgs = $this->db->query('select url from zj_user_circle_img where user_id=' . $u['id']. ' limit 3')->result_array();
                    
                    $arr_imgs = array();
                    foreach ($imgs as $img) {
                        array_push($arr_imgs, $img['url']);
                    }
                    $arr = array(
                            'id' => $u['id'],
                            'nickname' => $u['nickname'],
                            'photo' => $u['photo'],
                            'mobile' => $u['mobile'],
                            'friend_comment' => $friend['friend_comment'],
                            'description' => $friend['description'],
                            'is_friend' => 1,
                            'is_blacklist' => $is_blacklist,
                            'imgs' => $arr_imgs,
                    );
                    Util::success(10000, '成功', array('user' => $arr));
                } else {// 不允许看朋友圈
                    $arr = array(
                            'id' => $u['id'],
                            'nickname' => $u['nickname'],
                            'photo' => $u['photo'],
                            'mobile' => $u['mobile'],
                            'friend_comment' => $friend['friend_comment'],
                            'description' => $friend['description'],
                            'is_friend' => 1,
                            'is_blacklist' => $is_blacklist,
                    );
                    Util::success(10000, '成功', array('user' => $arr));
                }
            } else { // 不是自己的好友
                $u['is_friend'] = 0;
                $u['imgs'] = array();
                Util::success(10000, '成功', array('user' => $u));
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    // 发布文本、图片、视频朋友圈时的共同操作
    private function add_txt_to_circle($array, $user) {
        if($array['see_type'] == 'public') {
            //////////////todo 设置url////////////
            $arr = array(
                    'user_id' => $user['id'],
                    'content' => $array['txt'],
                    'location' => $array['location'],
                    'longitude' => $array['longitude'],
                    'latitude' => $array['latitude'],
                    'create_time' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('user_circle', $arr);
            $circle_id = $this->db->insert_id();
            return $circle_id;
        } elseif ($array['see_type'] == 'self') {
            //////////////todo 设置url////////////
            $arr = array(
                    'user_id' => $user['id'],
                    'content' => $array['txt'],
                    'location' => $array['location'],
                    'longitude' => $array['longitude'],
                    'latitude' => $array['latitude'],
                    'create_time' => date('Y-m-d H:i:s'),
                    'see_type' => 'self',
            );
            $this->db->insert('user_circle', $arr);
            $circle_id = $this->db->insert_id();
            return $circle_id;
        } elseif ($array['see_type'] == 'partial') {
            //////////////todo 设置url////////////
            $arr = array(
                    'user_id' => $user['id'],
                    'content' => $array['txt'],
                    'location' => $array['location'],
                    'longitude' => $array['longitude'],
                    'latitude' => $array['latitude'],
                    'create_time' => date('Y-m-d H:i:s'),
                    'see_type' => 'partial',
            );
            $this->db->insert('user_circle', $arr);
            $circle_id = $this->db->insert_id();
            
            $arr1 = array();
            $array['access_user_ids'] = json_decode($array['access_user_ids']);
            foreach ($array['access_user_ids'] as $user_id) {
                $item_arr = array(
                        'user_circle_id' => $circle_id,
                        'access_user_id' => $user_id,
                );
                array_push($arr1, $item_arr);
            }
            $this->db->insert_batch('user_circle_access', $arr1);
            
            $arr2 = array();
            $array['tip_user_ids'] = json_decode($array['tip_user_ids']);
            foreach ($array['tip_user_ids'] as $user_id) {
                $item_arr = array(
                        'user_circle_id' => $circle_id,
                        'tip_user_id' => $user_id,
                );
                array_push($arr2, $item_arr);
            }
            $this->db->insert_batch('user_circle_tip', $arr2);
            return $circle_id;
        } else {
            Util::error(30000, '参数有误', '');
        }
    }
    
    // 朋友圈发布文字信息
    public function publish_txt($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $this->db->trans_begin();
            $this->add_txt_to_circle($array, $user);
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
            $circle_id = $this->add_txt_to_circle($array, $user);
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
                            'user_circle_id' => $circle_id,
                            'url' => $file_path,
                    );
                    array_push($data, $arr);
                }
            }
            $this->db->insert_batch('user_circle_img', $data);

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
            $circle_id = $this->add_txt_to_circle($array, $user);
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
                        'user_circle_id' => $circle_id,
                        'url' => $path,
                        'thumbnail_url' => $thumbnail_path,
                );
                $this->db->insert('user_circle_video',$arr);
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

    // 获取自己的朋友圈
    public function get_circle($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $limit = 10;
            $offset = ($array['page']-1) * $limit;
            $str = 'select DISTINCT zj_user.photo,zj_user_circle.id,zj_user_circle.user_id as owner_id,zj_user_circle.content,zj_user_circle.location,zj_user_circle.longitude,zj_user_circle.latitude,zj_user_circle.thumbs_up_count,zj_user_circle.comment_count,zj_user_circle.create_time from zj_user_circle left join zj_friend on zj_user_circle.user_id=zj_friend.user_id left join zj_user_circle_access on zj_user_circle_access.user_circle_id=zj_user_circle.id left join zj_user on zj_user.id=zj_user_circle.user_id where zj_friend.is_deleted=' . NOT_DELETED . ' and ((zj_friend.see_friend_circle='.CAN_SEE.' and zj_friend.friend_id='.$user['id'].' and (zj_user_circle.see_type="public" or zj_user_circle_access.access_user_id='.$user['id'].')) or zj_user_circle.user_id='.$user['id'].') order by zj_user_circle.id desc limit '. $limit . ' offset ' . $offset;
            $circles = $this->db->query($str)->result_array();
            // 获取评论和点赞
            $arr_comment = array();
            $arr_thumbs_up = array();
            foreach ($circles as $key => $circle) {
                // 获取评论
                $comments = $this->db->query('select zj_user_circle_comment.id,zj_user_circle_comment.content,zj_user_circle_comment.comment_user_id,zj_user.nickname,zj_user.photo from zj_user_circle_comment left join zj_user on zj_user.id=zj_user_circle_comment.comment_user_id where parent_id='.$circle['id']. ' and comment_type="for_circle"')->result_array();
                foreach ($comments as $comment) {
                    if (!empty($comment)) {
                        $item = array(
                                'id' => $comment['id'],
                                'parent_id' => $circle['id'],
                                'type' => 'for_circle',
                                'content' => $comment['content'],
                                'comment_user_id' => $comment['comment_user_id'],
                                'comment_user_nickname' => $comment['nickname'],
                                'comment_user_photo' => $comment['photo'],
                                
                        );
                        array_push($arr_comment, $item);
                    
                        $parent_id = $comment['id'];
                        while (true) {
                            $co = $this->db->query('select zj_user_circle_comment.id,zj_user_circle_comment.content,zj_user_circle_comment.comment_user_id,zj_user.nickname,zj_user.photo from zj_user_circle_comment left join zj_user on zj_user.id=zj_user_circle_comment.comment_user_id where parent_id='.$parent_id. ' and comment_type="for_comment" limit 1')->row_array();
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
                // 获取点赞
                $thumbs = $this->db->query('select zj_user_circle_thumbs_up.user_circle_id,zj_user_circle_thumbs_up.thumbs_up_user_id,zj_user_circle_thumbs_up.thumbs_up_time,zj_user.nickname as comment_user_nickname,zj_user.photo as comment_user_photo from zj_user_circle_thumbs_up left join zj_user on zj_user.id=zj_user_circle_thumbs_up.thumbs_up_user_id where user_circle_id='.$circle['id'])->result_array();
                foreach ($thumbs as $thumb) {
                    array_push($arr_thumbs_up, $thumb);
                }
                
                $circles[$key]['comments'] = $arr_comment;
                $circles[$key]['thumbs'] = $arr_thumbs_up;
            }
            Util::success(10000, '成功', array('circles' => $circles));
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 评论
     */
    public function circle_comment($array)
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
                $this->db->insert('user_circle_comment',$arr1);
            } else { // 直接对帖子的评论
                $arr = array(
                        'parent_id' => $array['circle_id'],
                        'comment_user_id' => $user['id'],
                        'comment_time' => date('Y-m-d H:i:s'),
                        'comment_type' => 'for_circle',
                        'content' => $array['comment'],
                );
                $this->db->insert('user_circle_comment',$arr);
                $circle = $this->db->query('select * from zj_user_circle where id='.$array['circle_id']. ' limit 1')->row_array();
                $count = $circle['comment_count'] + 1;
                $this->db->query('update zj_user_circle set comment_count=' . $count . ' where id=' . $array['circle_id']);
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
     * 评论
     */
    public function circle_thumbs_up($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $this->db->trans_begin();
            $arr = array(
                    'user_circle_id' => $array['circle_id'],
                    'thumbs_up_user_id' => $user['id'],
                    'thumbs_up_time' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('user_circle_thumbs_up',$arr);
            $circle = $this->db->query('select * from zj_user_circle where id='.$array['circle_id']. ' limit 1')->row_array();
            $count = $circle['thumbs_up_count'] + 1;
            $this->db->query('update zj_user_circle set thumbs_up_count=' . $count . ' where id=' . $array['circle_id']);
            
            if($this->db->trans_status()==true) {
                $this->db->trans_commit();
                Util::success(10000, '点赞成功', '');
            } else {
                $this->db->trans_rollback();
                Util::error(50000, '点赞失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 收藏
     */
    public function circle_collecte($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $arr = array(
                    'user_id' => $user['id'],
                    'user_circle_id' => $array['circle_id'],
            );
            if($this->db->insert('user_circle_collection', $arr)){
                Util::success(10000, '收藏成功', '');
            }
            else
            {
                Util::error(50000, '收藏失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 取消收藏某条朋友圈
     */
    public function circle_cancel_collection($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            if($this->db->delete('user_circle_collection', 'user_id='.$user['id'].' and user_circle_id='.$array['circle_id'])){
                Util::success(10000, '取消成功', '');
            }
            else
            {
                Util::error(50000, '取消失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 获取收藏的朋友圈
     */
    public function circle_collection($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $circles = $this->db->query('select zj_user_circle.id,zj_user_circle.user_id,zj_user_circle.content,zj_user_circle.url,zj_user_circle.extra_url,zj_user_circle.thumbs_up_count,zj_user_circle.comment_count,zj_user_circle.create_time from zj_user_circle_collection left join zj_user_circle on zj_user_circle_collection.user_circle_id=zj_user_circle.id where zj_user_circle_collection.user_id=' . $user['id'])->result_array();
            Util::success(10000, '成功', array('circles' => $circles));
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 删除某条朋友圈
     */
    public function circle_delete($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            if($this->db->delete('user_circle', 'user_id='.$user['id'].' and id='.$array['circle_id'])){
                //////////////todo 删除评论////////////
                Util::success(10000, '删除成功', '');
            }
            else
            {
                Util::error(50000, '删除失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }
    
    /**
     * 转发某条朋友圈
     */
    public function circle_forward($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $circle = $this->db->query('select * from zj_user_circle where id=' . $array['circle_id'] . ' limit 1')->row_array();
            //////////////todo 设置url////////////
            $arr = array(
                    'user_id' => $user['id'],
                    'content' => '',
                    'location' => '',
                    'longitude' => 0,
                    'latitude' => 0,
                    'extra_url' => $circle['url'],
                    'create_time' => date('Y-m-d H:i:s'),
            );
            if($this->db->insert('user_circle', $arr)){
                Util::success(10000, '转发成功', '');
            } else {
                Util::success(50000, '转发失败', '');
            }
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 获取收藏的帖子
     */
    public function tiezi_collection($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $limit = 10;
            $offset = ($array['page']-1) * $limit;
            $tiezis = $this->db->query('select tiezi.id,tiezi.user_id,tiezi.luntan_id,tiezi.luntan_block_id,tiezi.title,tiezi.content,tiezi.comment_count,tiezi.see_count,tiezi.is_essence,tiezi.has_img,zj_user.nickname as author_nickname,zj_user.photo as author_photo,zj_luntan_block.block_name from zj_user_tiezi_collection left join zj_luntan_tiezi as tiezi on zj_user_tiezi_collection.luntan_tiezi_id=tiezi.id left join zj_user on tiezi.user_id=zj_user.id left join zj_luntan_block on zj_luntan_block.id=tiezi.luntan_block_id where tiezi.user_id=' . $user['id'] . ' limit ' . $limit . ' offset ' . $offset)->result_array();
            Util::success(10000, '成功', array('tiezis' => $tiezis));
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 个人中心，我的帖子接口
     */
    public function my_tiezi($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $limit = 10;
            $offset = ($array['page']-1) * $limit;
            $tiezis = $this->db->query('select tiezi.id,tiezi.user_id,tiezi.title,tiezi.content,tiezi.comment_count,tiezi.see_count,tiezi.is_essence,tiezi.has_img,zj_luntan.luntan_name,zj_luntan_block.block_name from zj_luntan_tiezi as tiezi left join zj_luntan on zj_luntan.id=tiezi.luntan_id left join zj_luntan_block on zj_luntan_block.id=tiezi.luntan_block_id where tiezi.user_id=' . $user['id'] . ' limit ' . $limit . ' offset ' . $offset)->result_array();
            Util::success(10000, '成功', array('tiezis' => $tiezis));
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 个人中心，我的相册接口(我自己发布的朋友圈)
     */
    public function my_album($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $limit = 10;
            $offset = ($array['page']-1) * $limit;
            $circles = $this->db->query('select circle.id,circle.content,circle.content,circle.create_time,group_concat(img.url) as imgs,group_concat(video.url) as videos from zj_user_circle as circle left join zj_user_circle_img as img on img.user_circle_id=circle.id left join zj_user_circle_video as video on video.user_circle_id=circle.id where circle.user_id=' . $user['id'] . ' group by circle.id limit ' . $limit . ' offset ' . $offset)->result_array();
            // 并没有去重，因为朋友圈只能发图片或者视频，所有在left join两个表的时候应该不会有重复的img和video数据产生
            foreach ($circles as $key=>$circle) {
                $imgs = explode(',', $circle['imgs']);
                $videos = explode(',', $circle['videos']);
                $circles[$key]['imgs'] = $imgs;
                $circles[$key]['videos'] = $videos;
            }
            Util::success(10000, '成功', array('circles' => $circles));
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }

    /**
     * 获取某条朋友圈的评论，点赞
     */
    public function get_circle_comment_thumbs($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $arr = array();
            $imgs = array();
            $circle = $this->db->query('select id from zj_user_circle where id='.$array['circle_id'].' limit 1')->row_array();
            // 获取评论和点赞
            $arr_comment = array();
            $arr_thumbs_up = array();
            // 获取评论
            $comments = $this->db->query('select zj_user_circle_comment.id,zj_user_circle_comment.content,zj_user_circle_comment.comment_user_id,zj_user.nickname,zj_user.photo from zj_user_circle_comment left join zj_user on zj_user.id=zj_user_circle_comment.comment_user_id where parent_id='.$circle['id']. ' and comment_type="for_circle"')->result_array();
            foreach ($comments as $comment) {
                if (!empty($comment)) {
                    $item = array(
                            'id' => $comment['id'],
                            'parent_id' => $circle['id'],
                            'type' => 'for_circle',
                            'content' => $comment['content'],
                            'comment_user_id' => $comment['comment_user_id'],
                            'comment_user_nickname' => $comment['nickname'],
                            'comment_user_photo' => $comment['photo'],
        
                    );
                    array_push($arr_comment, $item);
        
                    $parent_id = $comment['id'];
                    while (true) {
                        $co = $this->db->query('select zj_user_circle_comment.id,zj_user_circle_comment.content,zj_user_circle_comment.comment_user_id,zj_user.nickname,zj_user.photo from zj_user_circle_comment left join zj_user on zj_user.id=zj_user_circle_comment.comment_user_id where parent_id='.$parent_id. ' and comment_type="for_comment" limit 1')->row_array();
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
            // 获取点赞
            $thumbs = $this->db->query('select zj_user_circle_thumbs_up.user_circle_id,zj_user_circle_thumbs_up.thumbs_up_user_id,zj_user_circle_thumbs_up.thumbs_up_time,zj_user.nickname as comment_user_nickname,zj_user.photo as comment_user_photo from zj_user_circle_thumbs_up left join zj_user on zj_user.id=zj_user_circle_thumbs_up.thumbs_up_user_id where user_circle_id='.$circle['id'])->result_array();
            foreach ($thumbs as $thumb) {
                array_push($arr_thumbs_up, $thumb);
            }
        
            $circle['comments'] = $arr_comment;
            $circle['thumbs'] = $arr_thumbs_up;
            Util::success(10000, '成功', array('circle' => $circle));
        } else {
            Util::error(40000, '未找到该对应用户', '');
        }
    }
}