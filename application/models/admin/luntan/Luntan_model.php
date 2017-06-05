<?php

class Luntan_model extends CI_Model
{
    /**
     * 展示所有的论坛
     */
    public function index($per_nums, $offset, $where)
    {
        $data = array();
        $query = $this->db->query('select * from zj_luntan ' . $where .' limit '  . $per_nums . ' offset ' . $offset);
        $da=$query->result_array();
        $count_query = $this->db->query('select count(*) as co from zj_luntan ' . $where);
        $total_query = $this->db->query('select count(*) as total_luntans from zj_luntan');
        $result = $count_query->row_array();
        $result_total = $total_query->row_array();
        
        $data['total_nums'] = $result['co'];
        $data['total_luntans'] = $result_total['total_luntans'];
        
        $data['query']=$da; //这里大家可能看的优点不明白，可以分别将$data和$data2打印出来看看是什么结果。
        return $data;
    }

    /**
     * 展示某个论坛的板块
     */
    public function blocks($luntan_id)
    {
        $blocks = $this->db->query('select * from zj_luntan_block where luntan_id='.$luntan_id)->result_array();
        return $blocks;
    }

    /**
     * 展示某个论坛的板块
     */
    public function block_delete($array)
    {
        $luntan_block_id = $array['luntan_block_id'];
        if ($this->db->delete('luntan_block', 'id='.$luntan_block_id)) {
            Util::success(10000, '成功', '');
        } else {
            Util::error(30000, '失败', '');
        }
    }

    /**
     * 展示某个论坛的板块
     */
    public function block_add($array)
    {
        $arr = array(
                'luntan_id' => $array['luntan_id'],
                'block_name' => $array['block_name'],
        );
        if ($this->db->insert('luntan_block', $arr)) {
            $luntan_block_id = $this->db->insert_id();
            Util::success(10000, '成功', array('luntan_block_id' => $luntan_block_id));
        } else {
            Util::error(30000, '失败', '');
        }
    }

    /**
     * 展示所有的帖子
     */
    public function tiezi($per_nums, $offset, $where)
    {
        $data = array();
        $query = $this->db->query('select tiezi.id,tiezi.title,tiezi.is_essence,tiezi.comment_count,tiezi.see_count,tiezi.create_time,zj_user.nickname,zj_luntan.luntan_name from zj_luntan_tiezi as tiezi left join zj_luntan on zj_luntan.id=tiezi.luntan_id left join zj_user on zj_user.id=tiezi.user_id ' . $where .' limit '  . $per_nums . ' offset ' . $offset);
        $da=$query->result_array();
        $count_query = $this->db->query('select count(*) as co from zj_luntan_tiezi as tiezi left join zj_luntan on zj_luntan.id=tiezi.luntan_id left join zj_user on zj_user.id=tiezi.user_id ' . $where);
        $total_query = $this->db->query('select count(*) as total_tiezis from zj_luntan_tiezi');
        $result = $count_query->row_array();
        $result_total = $total_query->row_array();
    
        $data['total_nums'] = $result['co'];
        $data['total_tiezis'] = $result_total['total_tiezis'];
    
        $data['query']=$da; //这里大家可能看的优点不明白，可以分别将$data和$data2打印出来看看是什么结果。
        return $data;
    }

    /**
     * 展示所有的帖子
     */
    public function delete($array)
    {
        $this->db->trans_begin();
        $luntan_id = $array['luntan_id'];
        $tiezi_ids = $this->db->query('select id from zj_luntan_tiezi where luntan_id='.$luntan_id)->result_array();
        
        if(sizeof($tiezi_ids) > 0) {
            $arr = array();
            foreach ($tiezi_ids as $tiezi) {
                array_push($arr, $tiezi['id']);
            }
            $arr = implode(',', $arr);
            $this->db->delete('luntan_tiezi_img', 'luntan_tiezi_id in ('.$arr.')');
            $this->db->delete('luntan_tiezi_video', 'luntan_tiezi_id in ('.$arr.')');
            $this->db->delete('luntan_tiezi', 'id in ('.$arr.')');
            $this->db->delete('luntan', 'id='.$luntan_id);
        } else {
            $this->db->delete('luntan', 'id='.$luntan_id);
        }
        if($this->db->trans_status() == true) {
            $this->db->trans_commit();
            Util::success(10000, '关注成功', '');
        } else {
            $this->db->trans_rollback();
            Util::error(50000, '关注失败', '');
        }
    }
}