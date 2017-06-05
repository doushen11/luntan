<?php
class Qun_model extends CI_Model{
    // 设置群头像
    private function update_qun_icon($group_id) {
        $users = $this->db->query('select * from zj_user_group left join zj_user on zj_user_group.user_id=zj_user.id where zj_user_group.group_id=' . $group_id . ' limit 9')->result_array();
        $imgs = array();
        foreach ($users as $key => $user) {
            $imgs[$key] = $user['photo'];
        }
        $this->load->helper('img_pinjie');
        $imgPinjie = new ImgPinjie();
        $icon = $imgPinjie->get_img($imgs);
        $this->db->query('update zj_group set icon="'.$icon.'" where id=' . $group_id);
    }
    
    // 根据token获取用户
    public function get_user($token)
    {
        $user = $this->db->query('select * from zj_user where token="' . $token . '" limit 1')->row_array();
    
        if(!empty($user)) {
            return $user;
        } else {
            return '';
        }
    }
    
    // 修改群的名字
    public function update_group_name($array)
    {
        $user = $this->get_user($array['token']);
        if($user != '') {
            $user_group = $this->db->query('select * from zj_user_group where user_id=' . $user['id'] . ' and group_id='.$array['group_id'].' limit 1')->row_array();
            if (!empty($user_group)) {
                $this->db->query('update zj_group set group_name="'.$array['group_name'].'" where id=' . $array['group_id']);
                Util::success(10000, '成功', '');
            } else {
                Util::error(50000, '不能更改群名字', '');
            }
        } else {
            Util::error(40000, '没有该用户', '');
        }
    }
    
    // 获取自己的群列表
    public function get_group_list($token)
    {
        $user = $this->get_user($token);
        if($user != '') {
            $groups = $this->db->query('select *,zj_group.id as id from zj_user_group left join zj_group on zj_user_group.group_id=zj_group.id where zj_user_group.user_id=' . $user['id']. ' and zj_user_group.in_address_book='.IN_BOOK)->result_array();
            Util::success(10000, '成功', array('groups' => $groups));
        } else {
            Util::error(40000, '没有该用户', '');
        }
    
    }
    
    // 根据数据库的群id获取单个群信息
    public function get_group($group_id, $token)
    {
        $user = $this->get_user($token);
        if($user != '') {
            $group = $this->db->query('select *,zj_group.id as id,zj_user_group.in_address_book from zj_group left join zj_user_group on zj_user_group.group_id=zj_group.id where zj_user_group.group_id=' . $group_id . ' and zj_user_group.user_id='.$user['id'].' limit 1')->row_array();
        
            if(!empty($group)) {
                return $group;
            } else {
                return '';
            }
        } else {
            Util::error(40000, '没有该用户', '');
        }
    }
    
    // 根据环信的群id获取单个群信息
    public function get_group_by_huanxingroupid($huanxin_group_id, $token)
    {
        $user = $this->get_user($token);
        if($user != '') {
            $group = $this->db->query('select *,zj_group.id as id,zj_user_group.in_address_book from zj_group left join zj_user_group on zj_user_group.group_id=zj_group.id where zj_group.huanxin_group_id=' . $huanxin_group_id . ' and zj_user_group.user_id='.$user['id'].' limit 1')->row_array();
    
            if(!empty($group)) {
                return $group;
            } else {
                return '';
            }
        } else {
            Util::error(40000, '没有该用户', '');
        }
    }
    
    //创建群
    public function create($token)
    {
        $user = $this->get_user($token);
        if($user != '') {

            $this->db->trans_begin();
            $huanxin_group_id = '';
            $huanxin = new Huanxin();
            $flag1 = false;
            $qun = array(
                    'groupname' => '群聊',
                    'desc' => 'this a qun',
                    'public' => true,
                    'maxusers' => 1000,
                    'owner' => $user['mobile'],
            );
            $result1 = $huanxin->createGroup($qun);
            LogUtil::info('huanxin result1----------------'.json_encode($result1), __METHOD__);
            
            if(empty($result1['error'])){
                $flag1 = true;
                $huanxin_group_id = $result1['data']['groupid'];
                $arr = array(
                        'group_name' => '群聊',
                        'owner_id' => $user['id'],
                        'create_time' => date('Y-m-d H:i:s'),
                        'huanxin_group_id' => $huanxin_group_id,
                        'icon' => $user['photo'],
                );
                $this->db->insert('group', $arr);
                $group_id = $this->db->insert_id();
                
                $arr2 = array(
                        'user_id' => $user['id'],
                        'group_id' => $group_id,
                );
                $this->db->insert('user_group', $arr2);
            }
            
            if($flag1 && $this->db->trans_status()==true) {
                $this->db->trans_commit();
                Util::success(10000, '创建成功', array('group_id' => $group_id, 'huanxin_group_id'=>$huanxin_group_id));
            } else {
                $this->db->trans_rollback();
                Util::error(50000, '创建失败', '');
            }
        } else {
            Util::error(40000, '没有该用户', '');
        }
    }

    // 添加群组用户
    public function add_member($array)
    {
        $user = $this->get_user($array['token']);
        $group = $this->get_group($array['group_id'], $array['token']);
        if($user != '' && $group != '') {
            $this->db->trans_begin();
            $array['user_ids'] = json_decode($array['user_ids']);
            foreach ($array['user_ids'] as $user_id) {
                $arr = array(
                        'user_id' => $user_id,
                        'group_id' => $array['group_id'],
                );
                $this->db->insert('user_group', $arr);
            }
            
            $count = sizeof($array['user_ids']);
            $this->db->query('update zj_group set currentusers=currentusers+'.$count.' where id=' . $array['group_id']);
            $huanxin = new Huanxin();
            $flag = false;
            
            $body_arr['usernames'] = array();
            foreach ($array['user_ids'] as $user_id) {
                $u = $this->db->query('select * from zj_user where id=' . $user_id . ' limit 1')->row_array();
                array_push($body_arr['usernames'], $u['mobile']);
            }
            $result = $huanxin->addGroupMembers($group['huanxin_group_id'], $body_arr);
            LogUtil::info('huanxin result----------------'.json_encode($result), __METHOD__);
        
            if(empty($result['error'])){
                $flag = true;
            }
        
            if($flag && $this->db->trans_status()==true) {
                $this->db->trans_commit();
                $this->update_qun_icon($array['group_id']);
                Util::success(10000, '添加成功', '');
            } else {
                $this->db->trans_rollback();
                Util::error(50000, '添加失败', '');
            }
        } else {
            Util::error(40000, '信息有误', '');
        }
    }
    
    // 获取群成员
    public function get_members($array, $type='local_group')
    {
        $user = $this->get_user($array['token']);
        if($type == 'huanxin_group') {
            $group = $this->db->query('select * from zj_group where huanxin_group_id="' . $array['huanxin_group_id'] . '" limit 1')->row_array();
        } else {
            $group = $this->get_group($array['group_id'], $array['token']);
        }
        $members = array();
        if($user != '' && $group != '') {
            $members = $this->db->query('select zj_user_group.user_id,zj_user_group.group_id,zj_user.mobile,zj_user.photo,zj_user.nickname from zj_user_group left join zj_user on zj_user_group.user_id=zj_user.id where zj_user_group.group_id='.$group['id'])->result_array();
            $friends = $this->db->query('select friend_id,friend_comment from zj_friend where user_id='.$user['id'] . ' and is_deleted='.NOT_DELETED)->result_array();
            foreach ($members as $key => $member) {
                $members[$key]['is_friend'] = 0;
                foreach ($friends as $friend) {
                    if ($member['user_id'] == $friend['friend_id']) {
                        $members[$key]['is_friend'] = 1;
                        $members[$key]['friend_comment'] = $friend['friend_comment'];
                    }
                }
            }
            Util::success(10000, '成功', array('members' => $members));
        } else {
            Util::error(40000, '信息有误',  '');
        }
    }

    // 删除群组用户
    public function remove_member($array)
    {
        $user = $this->get_user($array['token']);
        $group = $this->get_group($array['group_id'], $array['token']);
        if($user != '' && $group != '') {
            if($user['id'] == $group['owner_id']) {
                $this->db->trans_begin();
                $array['user_ids'] = json_decode($array['user_ids']);
                foreach ($array['user_ids'] as $user_id) {
                    $this->db->delete('user_group', 'user_id='.$user_id.' and group_id='.$array['group_id']);
                }
        
                $user_group = $this->db->query('select * from zj_user_group where group_id=' . $array['group_id'] . ' limit 1')->row_array();
                $count = sizeof($array['user_ids']);
                $this->db->query('update zj_group set owner_id=' . $user_group['user_id'] . ',currentusers=currentusers-'.$count.' where id=' . $array['group_id']);
                
                $huanxin = new Huanxin();
                $flag = false;
        
                $huanxin_arr = array();
                foreach ($array['user_ids'] as $user_id) {
                    $u = $this->db->query('select * from zj_user where id=' . $user_id . ' limit 1')->row_array();
                    array_push($huanxin_arr, $u['mobile']);
                }
                
                $body_str = implode(',', $huanxin_arr);
                $result = $huanxin->deleteGroupMembers($group['huanxin_group_id'], $body_str);
                LogUtil::info('huanxin result----------------'.json_encode($result), __METHOD__);
        
                if(empty($result['error'])){
                    $flag = true;
                }
        
                if($flag && $this->db->trans_status()==true) {
                    $this->db->trans_commit();
                    $this->update_qun_icon($array['group_id']);
                    Util::success(10000, '删除成功', '');
                } else {
                    $this->db->trans_rollback();
                    Util::error(50000, '删除失败', '');
                }
            } else {
                Util::error(20000, '没有权限删除成员', '');
            }
        } else {
            Util::error(40000, '信息有误', '');
        }
    }

    // 删除并退出群
    public function remove_group($array)
    {
        $user = $this->get_user($array['token']);
        $group = $this->get_group($array['group_id'], $array['token']);
        if($user != '' && $group != '') {
            $this->db->trans_begin();
            $this->db->delete('user_group', 'user_id='.$user['id'].' and group_id='.$array['group_id']);
            $this->db->query('update zj_group set currentusers=currentusers-1 where id=' . $array['group_id']);
            
            $huanxin = new Huanxin();
            
            // 自己是群主时，自己退出后设别人为群主
            if($user['id'] == $group['owner_id']) {
                $user_group = $this->db->query('select * from zj_user_group where group_id=' . $array['group_id'] . ' limit 1')->row_array();
                // 群里还有别人（包括自己）
                if(!empty($user_group)){
                    $this->db->query('update zj_group set owner_id=' . $user_group['user_id'] . ' where id=' . $array['group_id']);
                    // 转让群主
                    $u = $this->db->query('select mobile from zj_user where id='.$user_group['user_id'].' limit 1')->row_array();
                    $result = $huanxin->changeGroupOwner($group['huanxin_group_id'], array('newowner' => $u['mobile']));
                    LogUtil::info('huanxin result1----------------'.json_encode($result), __METHOD__);
                    // 退出群
                    $result = $huanxin->deleteGroupMember($group['huanxin_group_id'], $user['mobile']);
                    LogUtil::info('huanxin result2----------------'.json_encode($result), __METHOD__);
                } else { // 群里只剩下自己，自己退出后删除群
                    $this->db->delete('group', 'id='.$array['group_id']);
                    // 删除群
                    $result = $huanxin->deleteGroup($group['huanxin_group_id']);
                    LogUtil::info('huanxin result3----------------'.json_encode($result), __METHOD__);
                }
            } else {
                $result = $huanxin->deleteGroupMember($group['huanxin_group_id'], $user['mobile']);
                LogUtil::info('huanxin result4----------------'.json_encode($result), __METHOD__);
            }

            if($this->db->trans_status()==true) {
                $this->db->trans_commit();
                Util::success(10000, '删除成功', '');
            } else {
                $this->db->trans_rollback();
                Util::error(50000, '删除失败', '');
            }
        } else {
            Util::error(40000, '信息有误', '');
        }
    }
}