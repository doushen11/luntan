<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>工信后台管理系统</title>
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/css/css.css" />
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/css/pagination.css" />
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/jquery1.9.0.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/sdmenu.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/layer_pc/layer.js"></script>

<script>
	var login_url="<?php echo $this->config->item('http_url');?>admin/login/indexs?uri=<?php echo isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI']!=""?trim($_SERVER['REQUEST_URI']):"";?>";
	var destory_url="<?php echo $this->config->item('http_url');?>admin/login/destory";
	var http_url="<?php echo $this->config->item('http_url');?>";
	var base_url="<?php echo $this->config->item('base_url');?>";
</script>
<style>
	body,p,td,tr,text,input{font-family:'微软雅黑';}
</style>
</head>

<body>