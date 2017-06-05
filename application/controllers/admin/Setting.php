<?php
    class Setting extends CI_Controller
    {
        /**
         * 下载安装包
         */
        public function download() {
            $param = $this->input->get();
            if($param['phone'] == 'iphone') {
                $item = $this->db->query('select * from zj_package_version where phone="iphone" limit 1')->row_array();
                return $item['url'];
            } else if($param['phone'] == 'android') {
                $item = $this->db->query('select * from zj_package_version where phone="android" limit 1')->row_array();
                $file_name = $item['url'];
                $this->download_sdk($file_name);
            }
        }

        public function download_sdk($file_name) {
            header("Content-type:text/html;charset=utf-8");
            //用以解决中文不能显示出来的问题
            $file_name=iconv("utf-8","gb2312",$file_name);
            $file_sub_path=$_SERVER['DOCUMENT_ROOT'];
            $file_path=$file_sub_path.DIRECTORY_SEPARATOR.$file_name;
            //首先要判断给定的文件存在与否
            if(!file_exists($file_path)){
                echo "没有该文件";
                return ;
            }
            $fp=fopen($file_path,"r");
            $file_size=filesize($file_path);
            
            $file_name=strtolower(substr($file_name,strripos($file_name,'/')+1,1200));
            //下载文件需要用到的头
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length:".$file_size);
            Header("Content-Disposition: attachment; filename=".$file_name);
            $buffer=1024;
            $file_count=0;
            //向浏览器返回数据
            while(!feof($fp) && $file_count<$file_size){
                $file_con=fread($fp,$buffer);
                $file_count+=$buffer;
                echo $file_con;
            }
            fclose($fp);
        }
    }