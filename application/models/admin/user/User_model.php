<?php
    class User_model extends CI_Model
    {
        /**
         * 查看用户
         */
        public function index($per_nums,$offset, $where){
            $data = array();
            $query = $this->db->query('select * from zj_user ' . $where .' limit '  . $per_nums . ' offset ' . $offset);
            $da=$query->result_array();
            $count_query = $this->db->query('select count(*) as co from zj_user ' . $where);
            $total_query = $this->db->query('select count(*) as total_user from zj_user');
            $result = $count_query->row_array();
            $result_total = $total_query->row_array();
            
            $data['total_nums'] = $result['co'];
            $data['total_users'] = $result_total['total_user'];
            
            $data['query']=$da; //这里大家可能看的优点不明白，可以分别将$data和$data2打印出来看看是什么结果。
            return $data;
        }

        /**
         * 查看好友列表
         */
        public function friends($per_nums,$offset, $where, $user_id){
            $data = array();
            $query = $this->db->query('select * from zj_friend ' . $where .' limit '  . $per_nums . ' offset ' . $offset);
            $da=$query->result_array();
            $count_query = $this->db->query('select count(*) as co from zj_friend ' . $where);
            $total_query = $this->db->query('select count(*) as total_user from zj_friend where user_id='.$user_id.' and is_deleted='.NOT_DELETED);
            $result = $count_query->row_array();
            $result_total = $total_query->row_array();
        
            $data['total_nums'] = $result['co'];
            $data['total_users'] = $result_total['total_user'];
        
            $data['query']=$da; //这里大家可能看的优点不明白，可以分别将$data和$data2打印出来看看是什么结果。
            return $data;
        }

        /**
         * 查看好友列表
         */
        public function blacklist($per_nums,$offset, $where, $user_id){
            $data = array();
            $query = $this->db->query('select * from zj_blacklist ' . $where .' limit '  . $per_nums . ' offset ' . $offset);
            $da=$query->result_array();
            $count_query = $this->db->query('select count(*) as co from zj_blacklist ' . $where);
            $total_query = $this->db->query('select count(*) as total_user from zj_blacklist where user_id='.$user_id);
            $result = $count_query->row_array();
            $result_total = $total_query->row_array();
        
            $data['total_nums'] = $result['co'];
            $data['total_users'] = $result_total['total_user'];
        
            $data['query']=$da; //这里大家可能看的优点不明白，可以分别将$data和$data2打印出来看看是什么结果。
            return $data;
        }

        /**
         * 禁用某个用户
         */
        public function disable($user_id){
            if($this->db->query('update zj_user set is_disabled=' . IS_DISABLED . ' where id=' . $user_id)) {
                Util::success(10000, '处理成功', '');
            } else {
                Util::error(30000, '处理失败', '');
            }
        }

        /**
         * 启用某个用户
         */
        public function enable($user_id) {
            if($this->db->query('update zj_user set is_disabled=' . NOT_DISABLED . ' where id=' . $user_id)) {
                Util::success(10000, '处理成功', '');
            } else {
                Util::error(30000, '处理失败', '');
            }
        }

        /**
         * 投诉列表
         */
        public function complaint_list($per_nums,$offset, $where){
            $data = array();
            $sql = 'select complaint.id,complaint.content,u1.nickname as complaint_nickname,u1.mobile as complaint_mobile,u2.nickname as complainted_nickname,u2.mobile as complainted_mobile from zj_user_luntan_complaint as complaint left join zj_user as u1 on complaint.user_id=u1.id left join zj_user as u2 on complaint.complaint_user_id=u2.id ' . $where .' limit '  . $per_nums . ' offset ' . $offset;
            $query = $this->db->query($sql);
            $da=$query->result_array();
            $count_query = $this->db->query('select count(*) as co from zj_user_luntan_complaint as complaint left join zj_user as u1 on complaint.user_id=u1.id left join zj_user as u2 on complaint.complaint_user_id=u2.id ' . $where);
            $total_query = $this->db->query('select count(*) as total_complaints from zj_user_luntan_complaint as complaint left join zj_user as u1 on complaint.user_id=u1.id left join zj_user as u2 on complaint.complaint_user_id=u2.id');
            $result = $count_query->row_array();
            $result_total = $total_query->row_array();
        
            $data['total_nums'] = $result['co'];
            $data['total_complaints'] = $result_total['total_complaints'];
        
            $data['query']=$da; //这里大家可能看的优点不明白，可以分别将$data和$data2打印出来看看是什么结果。
            return $data;
        }
    }