<?php

class Circle_model extends CI_Model
{
    /**
     * 展示所有的朋友圈
     */
    public function index($per_nums, $offset, $where)
    {
        $data = array();
        $query = $this->db->query('select *,zj_user_circle.id as id from zj_user_circle left join zj_user on zj_user.id=zj_user_circle.user_id ' . $where .' limit '  . $per_nums . ' offset ' . $offset);
        $da=$query->result_array();
        $count_query = $this->db->query('select count(*) as co from zj_user_circle left join zj_user on zj_user.id=zj_user_circle.user_id ' . $where);
        $total_query = $this->db->query('select count(*) as total_circles from zj_user_circle left join zj_user on zj_user.id=zj_user_circle.user_id');
        $result = $count_query->row_array();
        $result_total = $total_query->row_array();
        
        $data['total_nums'] = $result['co'];
        $data['total_circles'] = $result_total['total_circles'];
        
        $data['query']=$da; //这里大家可能看的优点不明白，可以分别将$data和$data2打印出来看看是什么结果。
        return $data;
    }

    /**
     * 获取某个朋友圈的详细信息
     */
    public function circle_info($array)
    {
        $arr = array();
        $circle = $this->db->query('select *,zj_user_circle.id as id from zj_user_circle left join zj_user on zj_user.id=zj_user_circle.user_id where zj_user_circle.id=' . $array['circle_id'] . ' limit 1')->row_array();
        $arr['circle'] = $circle;
    
        $arr_comment = array();
        // 获取评论
    
        $comments = $this->db->query('select comment.id,comment.content,comment.comment_user_id,zj_user.nickname,zj_user.photo from zj_user_circle_comment as comment left join zj_user on zj_user.id=comment.comment_user_id where parent_id='.$circle['id']. ' and comment_type="for_circle"')->result_array();
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
                    $co = $this->db->query('select comment.id,comment.content,comment.comment_user_id,zj_user.nickname,zj_user.photo from zj_user_circle_comment as comment left join zj_user on zj_user.id=comment.comment_user_id where parent_id='.$parent_id. ' and comment_type="for_comment" limit 1')->row_array();
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
    
        $arr['imgs'] = $this->db->query('select url from zj_user_circle_img where user_circle_id=' . $circle['id'])->result_array();
        $arr['video'] = $this->db->query('select url,thumbnail_url from zj_user_circle_video where user_circle_id=' . $circle['id'])->result_array();

        return $arr;
    }
}