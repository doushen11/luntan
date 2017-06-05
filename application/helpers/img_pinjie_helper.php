<?php
// header('Content-Type: image/jpeg');
class ImgPinjie {
    function get_img($imgs) {
        // 把相对路径转换为绝对路径
        foreach ($imgs as $key => $img) {
            $imgs[$key] = FCPATH.$img;
        }
//         $imgs = array();
//         $imgs[0] = FCPATH.'public/upload/images/20170508/1.jpg';
//         $imgs[1] = FCPATH.'public/upload/images/20170508/2.jpg';
//         $imgs[2] = FCPATH.'public/upload/images/20170508/3.jpg';
//         $imgs[3] = FCPATH.'public/upload/images/20170508/4.jpg';
//         $imgs[4] = FCPATH.'public/upload/images/20170508/5.jpg';
//         $imgs[5] = FCPATH.'public/upload/images/20170508/6.jpg';
//         $imgs[6] = FCPATH.'public/upload/images/20170508/7.jpg';
//         $imgs[7] = FCPATH.'public/upload/images/20170508/8.jpg';
//         $imgs[8] = FCPATH.'public/upload/images/20170508/9.png';
        $target  = FCPATH.'public/picture/qun_bg.jpg'; //背景图片
        
        $folder = md5(mt_rand(100000,999999));
        $target_img = imagecreatefromjpeg($target);

        // 创建保存临时图片的文件夹
        $folder = FCPATH.'public/picture/tmp/'.$folder;
        if (!file_exists($folder)){
            mkdir($folder); //创建文件夹
            chmod($folder,0777); // 设置权限
        } else {
            LogUtil::info('需创建的临时文件夹已经存在', __METHOD__);
        }

        // 创建保存群头像的文件夹
        $fo = FCPATH.'public/picture/qun';
        if (!file_exists($fo)){
            mkdir($fo); //创建文件夹
            chmod($fo,0777); // 设置权限
        } else {
            LogUtil::info('需创建的群头像文件夹已经存在', __METHOD__);
        }

        $count = sizeof($imgs);
        // 生成新的图片，保存到临时文件夹里
        foreach ($imgs as $key => $img) {
            $arr = $this->img_tailoring($img);
            $new_img_name = $key.'.'.$arr['extname'];
            $this->img_resizeimage($arr['im'], $folder, $new_img_name, $arr['extname'], $count);
        }

        $tmpimgs = $this->get_files($folder);
        foreach ($tmpimgs as $k => $v) {
            $arr = $this->img_tailoring($v);
            $source[$k]['source'] = $arr['im'];
            $source[$k]['size'] = getimagesize($v);
        }

        $file_path = $this->create_target_img($target_img, $count, $source);
        
        // 删除临时文件夹及里面的文件
        $this->delete_files($folder);
        return $file_path;
    }
    
    /**
     * 生成目标图片
     */
    public function create_target_img($target_img, $count, $source) {
        error_reporting(1);
        switch ($count) {
            case 9:
                imagecopy($target_img, $source[0]['source'], 0, 0, 0, 0, 50, 50);
                imagecopy($target_img, $source[1]['source'], 50, 0, 0, 0, 50, 50);
                imagecopy($target_img, $source[2]['source'], 100, 0, 0, 0, 50, 50);
                imagecopy($target_img, $source[3]['source'], 0, 50, 0, 0, 50, 50);
                imagecopy($target_img, $source[4]['source'], 50, 50, 0, 0, 50, 50);
                imagecopy($target_img, $source[5]['source'], 100, 50, 0, 0, 50, 50);
                imagecopy($target_img, $source[6]['source'], 0, 100, 0, 0, 50, 50);
                imagecopy($target_img, $source[7]['source'], 50, 100, 0, 0, 50, 50);
                imagecopy($target_img, $source[8]['source'], 100, 100, 0, 0, 50, 50);
            break;
            case 8:
                imagecopy($target_img, $source[0]['source'], 0, 0, 0, 0, 50, 50);
                imagecopy($target_img, $source[1]['source'], 50, 0, 0, 0, 50, 50);
                imagecopy($target_img, $source[2]['source'], 100, 0, 0, 0, 50, 50);
                imagecopy($target_img, $source[3]['source'], 0, 50, 0, 0, 50, 50);
                imagecopy($target_img, $source[4]['source'], 50, 50, 0, 0, 50, 50);
                imagecopy($target_img, $source[5]['source'], 100, 50, 0, 0, 50, 50);
                imagecopy($target_img, $source[6]['source'], 0, 100, 0, 0, 50, 50);
                imagecopy($target_img, $source[7]['source'], 50, 100, 0, 0, 50, 50);
            break;
            case 7:
                imagecopy($target_img, $source[0]['source'], 50, 0, 0, 0, 50, 50);
                imagecopy($target_img, $source[1]['source'], 0, 50, 0, 0, 50, 50);
                imagecopy($target_img, $source[2]['source'], 50, 50, 0, 0, 50, 50);
                imagecopy($target_img, $source[3]['source'], 100, 50, 0, 0, 50, 50);
                imagecopy($target_img, $source[4]['source'], 0, 100, 0, 0, 50, 50);
                imagecopy($target_img, $source[5]['source'], 50, 100, 0, 0, 50, 50);
                imagecopy($target_img, $source[6]['source'], 100, 100, 0, 0, 50, 50);
            break;
            case 6:
                imagecopy($target_img, $source[0]['source'], 0, 25, 0, 0, 50, 50);
                imagecopy($target_img, $source[1]['source'], 50, 25, 0, 0, 50, 50);
                imagecopy($target_img, $source[2]['source'], 100, 25, 0, 0, 50, 50);
                imagecopy($target_img, $source[3]['source'], 0, 75, 0, 0, 50, 50);
                imagecopy($target_img, $source[4]['source'], 50, 75, 0, 0, 50, 50);
                imagecopy($target_img, $source[5]['source'], 100, 75, 0, 0, 50, 50);
            break;
            case 5:
                imagecopy($target_img, $source[0]['source'], 25, 25, 0, 0, 50, 50);
                imagecopy($target_img, $source[1]['source'], 75, 25, 0, 0, 50, 50);
                imagecopy($target_img, $source[2]['source'], 0, 75, 0, 0, 50, 50);
                imagecopy($target_img, $source[3]['source'], 50, 75, 0, 0, 50, 50);
                imagecopy($target_img, $source[4]['source'], 100, 75, 0, 0, 50, 50);
            break;
            case 4:
                imagecopy($target_img, $source[0]['source'], 0, 0, 0, 0, 75, 75);
                imagecopy($target_img, $source[1]['source'], 75, 0, 0, 0, 75, 75);
                imagecopy($target_img, $source[2]['source'], 0, 75, 0, 0, 75, 75);
                imagecopy($target_img, $source[3]['source'], 75, 75, 0, 0, 75, 75);
            break;
            case 3:
                imagecopy($target_img, $source[0]['source'], 37.5, 0, 0, 0, 75, 75);
                imagecopy($target_img, $source[1]['source'], 0, 75, 0, 0, 75, 75);
                imagecopy($target_img, $source[2]['source'], 75, 75, 0, 0, 75, 75);
            break;
            case 2:
                imagecopy($target_img, $source[0]['source'], 0, 37.5, 0, 0, 75, 75);
                imagecopy($target_img, $source[1]['source'], 75, 37.5, 0, 0, 75, 75);
            break;
            case 1:
                imagecopy($target_img, $source[0]['source'], 0, 0, 0, 0, 150, 150);
            break;
            default:
                ;
            break;
        }
        $file_path = 'public/picture/qun/'.md5(mt_rand(100000,999999)).'.jpg';
        imagejpeg($target_img, FCPATH.$file_path);
        return $file_path;
    }
    
    /**
     * 生成图片
     */
    function img_tailoring($image)
    {
        if (!file_exists($image)){
            $image = FCPATH.'public/picture/user_photo.jpg';
        }
        
        $extname=strtolower(substr($image,strrpos($image,".")+1,1200));
        if($extname=="jpg" || $extname=="jpeg")
        {
            $im=imagecreatefromjpeg($image);//参数是图片的存方路径
        }
        elseif($extname=="png")
        {
            $im=imagecreatefrompng($image);//参数是图片的存方路径
        }
        elseif($extname=="gif")
        {
            $im=imagecreatefromgif($image);//参数是图片的存方路径
        }
        $arr = array(
                'im' => $im,
                'extname' => $extname,
        );
        return $arr;
    }
    
    /**
     * 改变图片大小
     */
    function img_resizeimage($im, $folder, $new_img_name, $extname, $count)
    {
        $pic_width = imagesx($im); // 获取图像宽度
        $pic_height = imagesy($im); // 获取图像高度
        
        $newwidth = 50; // 设置宽度
        $newheight = 50; // 设置高度
        switch ($count) {
            case 9:
                break;
            case 8:
                break;
            case 7:
                break;
            case 6:
                break;
            case 5:
                break;
            case 4:
                $newwidth = 75; // 设置宽度
                $newheight = 75; // 设置高度
                break;
            case 3:
                $newwidth = 75; // 设置宽度
                $newheight = 75; // 设置高度
                break;
            case 2:
                $newwidth = 75; // 设置宽度
                $newheight = 75; // 设置高度
                break;
            case 1:
                $newwidth = 150; // 设置宽度
                $newheight = 150; // 设置高度
                break;
            default:
                ;
                break;
        }
        
        
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

        $file = $folder.'/'.$new_img_name;
        if($extname=="jpg" || $extname=="jpeg")
        {
            imagejpeg($newim, $file);
        }
        elseif($extname=="png")
        {
            $c=imagecolorallocatealpha($newim , 0 , 0 , 0 ,127);//拾取一个完全透明的颜色
            imagealphablending($newim ,false);//关闭混合模式，以便透明颜色能覆盖原画布
            imagefill($newim , 0 , 0, $c);//填充
            imagesavealpha($newim ,true);//设置保存PNG时保留透明通道信息
            imagepng($newim, $file);
        }
        elseif($extname=="gif")
        {
            imagegif($newim, $file);
        }
        
        imagedestroy($newim);
    }
    
    // 获取指定文件夹下的所有文件的名字
    function get_files($folder) {
        $arr = array();
        $filesnames = scandir($folder);
        foreach ($filesnames as $key=>$name) {
            if (!is_dir($name)) {
                array_push($arr, $folder.'/'.$name);
            }
        }
        return $arr;
    }
    
    // 获取指定文件夹下的所有文件的名字
    function delete_files($folder) {
        //先删除目录下的文件：
        $dh=opendir($folder);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$folder."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }
        
        closedir($dh);
        //删除当前文件夹：
        if(rmdir($folder)) {
            return true;
        } else {
            return false;
        }
    }
}