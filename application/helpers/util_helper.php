<?php
class Util {
    static function get_ip() {
        if(getenv('HTTP_CLIENT_IP')){
            $ip=getenv('HTTP_CLIENT_IP');
        }elseif(getenv('HTTP_X_FORWARDED_FOR')){
            $ip=getenv('HTTP_X_FORWARDED_FOR');
        }elseif(getenv('HTTP_X_FORWARDED')){
            $ip=getenv('HTTP_X_FORWARDED');
        }elseif(getenv('HTTP_FORWARDED_FOR')){
            $ip=getenv('HTTP_FORWARDED_FOR');
        }elseif(getenv('HTTP_FORWARDED')){
            $ip=getenv('HTTP_FORWARDED');
        }else{
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    static function success($code,$message,$_array,$type = 0){
        $result_array = array("code"=>$code,"message"=>$message,"resultCode"=>$_array);
        $obj = json_encode($result_array);
        echo trim($obj);
    }

    static function error($code,$message,$_array,$type = 0){
        $result_array = array("code"=>$code,"message"=>$message,"resultCode"=>$_array);
        $obj = json_encode($result_array);
        echo trim($obj);
        die;
    }
}