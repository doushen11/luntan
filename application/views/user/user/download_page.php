<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<style type="text/css">
.code-block{
    width: 160px;
    margin: 0px auto;
    text-align: center;
}

.code-block a {
    font-size: 0.8rem;
}

.code-block img {
    width: 160px;
    height: 160px;
}
</style>

</head>

<body>
<div id="content" class="">
    <?php
        $this->load->helper('qr');
        // 苹果手机用户端安装包链接
        $filename_iphone= QrUtil::generate_qrcode($query['url']);
        
        // 安卓手机用户端安装包链接
        $android_url = base_url().'index.php/admin/setting/download?phone=android';
        $filename_android= QrUtil::generate_qrcode($android_url);
    ?>
    
    <div class="code-block">
        <img src="<?php echo base_url().$filename_iphone;?>" />
        <a href="<?php echo $query['url'];?>">iphone用户端</a>
    </div>
    <div class="code-block">
        <img src="<?php echo base_url().$filename_android;?>" />
        <a href="<?php echo base_url().'index.php/admin/setting/download?phone=android';?>">android用户端</a>
    </div>
</div>
<script type="text/javascript">
</script>
</body>
</html>
