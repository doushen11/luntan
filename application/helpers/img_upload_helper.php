<?php
  class ImgUpload {
      //文件图片
      public function upload($files)
      {
          set_time_limit(0);
          $file=$this->img_upload($files,$result,1,"",1,10000,1000,1000);
          LogUtil::info('load file result: ' . $file, __METHOD__);
          if($result=="ok")
          {
              return $file;
          }
          else
          {
              return 1;
          }
      }
      
      function img_upload($file,&$result,$upload=null,$file1=null,$tailoring=null,$size=null,$maxwidth=null,$maxheight=null)
      {
          //自定义上传图片函数
          //print_r($file);die();
          $root=FCPATH;
          $size==""?$size=4000:$size=$size;
          $p_name  = $file["name"];
          $p_type  = $file["type"];
          $p_tmp   = $file["tmp_name"];
          $p_error = $file["error"];
          $p_size  = $file["size"];
          	
          if(substr_count($p_name,".")==0)
          {
              $p_name=date("YmdHis").substr(microtime(),2,8).".".$p_name;
          }
          	
          //dump($file);
          $extname=strtolower(substr($p_name,strrpos($p_name,".")+1,1000));
      
          if($upload=="" && $extname=="")
          {
              $result="ok";
              return $file1;
          }else{
      
              if($extname==""){
                  $result="error";
                  return "请选择上传文件";
              }elseif($p_error>0){
                  $result="error";
                  return "上传失败，可能是图片过大，图片大小不能超过".$size."KB";
              }elseif(substr_count("jpg_png_jpeg_gif",$extname)==0){
                  $result="error";
                  return "请上传图片文件";
              }elseif(substr_count($p_type,"image")==0 && substr_count($p_type,"application")==0){
                  $result="error";
                  return "请上传图片文件!";
              }elseif($p_size>($size*1024)){
                  $result="error";
                  return "图片大小不能超过".$size."KB";
              }else{
                  if(!is_uploaded_file($p_tmp)){
                      $result="error";
                      return "上传失败，未知错误";
                  }else{
                      if(!is_dir($root."public/upload")){
                          mkdir($root."public/upload");
                      }
                      if(!is_dir($root."public/upload/images")){
                          mkdir($root."public/upload/images");
                      }
                      if(!is_dir($root."public/upload/images/".date("Ymd"))){
                          mkdir($root."public/upload/images/".date("Ymd"));
                      }
                      $newname="recson_".date("YmdHis").substr(microtime(),2,8).rand(0,99999).".".$extname;
                      $newpic=$root."public/upload/images/".date("Ymd")."/".$newname;
                      $newurl="public/upload/images/".date("Ymd")."/".$newname;
                      if($file1==""){
                          $newpic=$root."public/upload/images/".date("Ymd")."/".$newname;
                          $newurl="public/upload/images/".date("Ymd")."/".$newname;
                      }else{
                          $newpic=$root.$file1;
                          $newurl=$file1;
                      }
                      if(move_uploaded_file($p_tmp,$newpic)){
                          $result="ok";
                          $tailoring!=""?$this->img_tailoring($newurl,$maxwidth,$maxheight):"";
                          return $newurl;
                      }else{
                          $result="error";
                          return "系统链接超时，请重试";
                      }
                  }
              }
          }
      }
      
      function img_tailoring($images,$maxwidth=null,$maxheight=null)
      {
          error_reporting(0);
          $images=ltrim($images,"/");
          $files=strtolower(substr($images,strrpos($images,"/")+1,1200));
          $extname=strtolower(substr($files,strrpos($files,".")+1,1200));
          if($maxwidth==""){
              $maxwidth="600";//设置图片的最大宽度
          }
          if($maxheight==""){
              $maxheight="600";//设置图片的最大高度
          }
          	
          $name=strtolower(substr($images,0,strrpos($images,".")));//图片的名称，随便取吧
          $filetype=".".$extname;//图片类型
          if($extname=="jpg" || $extname=="jpeg")
          {
              $im=imagecreatefromjpeg(FCPATH."/".$images);//参数是图片的存方路径
          }
          elseif($extname=="png")
          {
              $im=imagecreatefrompng(FCPATH."/".$images);//参数是图片的存方路径
          }
          elseif($extname=="gif")
          {
              $im=imagecreatefromgif(FCPATH."/".$images);//参数是图片的存方路径
          }
          $this->img_resizeimage($im,$maxwidth,$maxheight,$name,$filetype);//调用上面的函数
      }
      
      function img_resizeimage($im,$maxwidth,$maxheight,$name,$filetype)
      {
          error_reporting(0);
          $pic_width = imagesx($im);
          $pic_height = imagesy($im);
          	
          if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
          {
              if($maxwidth && $pic_width>$maxwidth)
              {
                  $widthratio = $maxwidth/$pic_width;
                  $resizewidth_tag = true;
              }
              	
              if($maxheight && $pic_height>$maxheight)
              {
                  $heightratio = $maxheight/$pic_height;
                  $resizeheight_tag = true;
              }
              	
              if($resizewidth_tag && $resizeheight_tag)
              {
                  if($widthratio<$heightratio)
                      $ratio = $widthratio;
                      else
                          $ratio = $heightratio;
              }
              	
              if($resizewidth_tag && !$resizeheight_tag)
                  $ratio = $widthratio;
      
                  if($resizeheight_tag && !$resizewidth_tag)
                      $ratio = $heightratio;
                      $newwidth = $pic_width * $ratio;
                      $newheight = $pic_height * $ratio;
                      	
                      if(function_exists("imagecopyresampled"))
                      {
                          $newim = imagecreatetruecolor($newwidth,$newheight);//PHP系统函数
                          imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);//PHP系统函数
                      }
                      else
                      {
                          $newim = imagecreate($newwidth,$newheight);
                          imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
                      }
                      	
                      $name = $name.$filetype;
                      imagejpeg($newim,$name);
                      imagedestroy($newim);
          }
          else
          {
              $name = $name.$filetype;
              imagejpeg($im,$name);
          }
      }
  }