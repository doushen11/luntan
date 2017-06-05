<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 首页数据获取
* @author lanss
**/
class Home_model extends CI_Model
{

    private $dbprefix = "";

    public $times;

    function __construct()
    {
        parent::__construct();
        $this->dbprefix=$this->db->dbprefix;
    }

    function getUsersData()
    {
        $data = $this->times;
        foreach($data as $k => $v){
            $_sql = "SELECT count(*) AS `num` FROM zj_users WHERE `login_time` >= {$v['start']} AND `login_time` <= {$v['end']}";
            $_result = $this->db->query($_sql)->result_array();
            if(isset($_result[0]['num'])){
                $data[$k]['value'] = (int)$_result[0]['num'];
            }else{
                $data[$k]['value'] = 0;
            }
        }
        return $data;
    }

}