<?php
    class Qun_model extends CI_Model
    {
        /**
         * 展示所有的群
         */
        public function index($per_nums,$offset, $where){
            $data = array();
//             var_dump('select t.id,t.owner_id,t.group_name,t.maxusers,t.currentusers,t.create_time,zj_user.mobile,zj_user.nickname from (select DISTINCT zj_group.id as id,zj_user.id as uid,zj_group.owner_id,zj_group.group_name,zj_group.maxusers,zj_group.currentusers,zj_group.create_time,zj_user.nickname,zj_user.mobile from zj_group left join zj_user_group on zj_group.id=zj_user_group.group_id left join zj_user on zj_user_group.user_id=zj_user.id ' . $where .' group by zj_group.id limit '  . $per_nums . ' offset ' . $offset . ') as t left join zj_user on t.owner_id=zj_user.id');die;
            $query = $this->db->query('select t.id,t.owner_id,t.group_name,t.maxusers,t.currentusers,t.create_time,zj_user.mobile,zj_user.nickname from (select DISTINCT zj_group.id as id,zj_user.id as uid,zj_group.owner_id,zj_group.group_name,zj_group.maxusers,zj_group.currentusers,zj_group.create_time,zj_user.nickname,zj_user.mobile from zj_group left join zj_user_group on zj_group.id=zj_user_group.group_id left join zj_user on zj_user_group.user_id=zj_user.id ' . $where .' group by zj_group.id limit '  . $per_nums . ' offset ' . $offset . ') as t left join zj_user on t.owner_id=zj_user.id');
            $da=$query->result_array();
            $count_query = $this->db->query('select count(*) as co from (select DISTINCT zj_group.id as id from zj_group left join zj_user_group on zj_group.id=zj_user_group.group_id left join zj_user on zj_user_group.user_id=zj_user.id ' . $where . ' group by zj_group.id) as t');
            
            $total_query = $this->db->query('select count(*) as total_quns from zj_group');
            $result = $count_query->row_array();
            $result_total = $total_query->row_array();
            
            $data['total_nums'] = $result['co'];
            $data['total_quns'] = $result_total['total_quns'];
            
            $data['query']=$da; //这里大家可能看的优点不明白，可以分别将$data和$data2打印出来看看是什么结果。
            return $data;
        }

        /**
         * 根据群id查看群成员
         */
        public function members($per_nums,$offset, $where){
            $data = array();
//             var_dump('select zj_group.group_name,zj_user.nickname,zj_user.mobile,zj_user.photo from zj_group left join zj_user_group on zj_group.id=zj_user_group.group_id left join zj_user on zj_user_group.user_id=zj_user.id ' . $where);die;
            $query = $this->db->query('select zj_group.group_name,zj_group.currentusers,zj_user.nickname,zj_user.mobile,zj_user.photo,zj_user.registe_time,zj_user.login_time from zj_group left join zj_user_group on zj_group.id=zj_user_group.group_id left join zj_user on zj_user_group.user_id=zj_user.id ' . $where. ' limit '  . $per_nums . ' offset ' . $offset);
            $da=$query->result_array();
            $count_query = $this->db->query('select count(*) as co from zj_group left join zj_user_group on zj_group.id=zj_user_group.group_id left join zj_user on zj_user_group.user_id=zj_user.id ' . $where);
        
            $result = $count_query->row_array();
        
            $data['total_nums'] = $result['co'];
        
            $data['query']=$da; //这里大家可能看的优点不明白，可以分别将$data和$data2打印出来看看是什么结果。
            return $data;
        }

        /**
         * 删除某个群
         */
        public function delete($qun_id) {
            $this->db->trans_begin();
            $group = $this->db->query('select huanxin_group_id from zj_group where id='.$qun_id.' limit 1')->row_array();
            $this->db->delete('group', 'id='.$qun_id);
            $this->db->delete('user_group', 'group_id='.$qun_id);
            $huanxin = new Huanxin();
            $result = $huanxin->deleteChatRoom($group['huanxin_group_id']);
            LogUtil::info('huanxin result----------------'.json_encode($result), __METHOD__);
            if(empty($result['error']) && $this->db->trans_status()==true) {
                $this->db->trans_commit();
                Util::success(10000, '删除成功', '');
            } else {
                $this->db->trans_rollback();
                Util::error(30000, '删除失败', '');
            }
        }
    }