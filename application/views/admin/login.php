<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新微城后台登录</title>
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/bootstrap/css/bootstrap.min.css" />
 
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/jquery1.9.0.min.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/bootstrap.min.js"></script>
<script src="<?php echo $this->config->item('base_url');?>public/layer_pc/layer.js"></script>
<style type="text/css">
body{ background:#0066A8 url(<?php echo $this->config->item('base_url');?>public/admin/img/login_bg.png) no-repeat center 0px;}
.tit{ margin:auto; margin-top:170px; text-align:center; width:350px; padding-bottom:20px;}
.login-wrap{ width:580px; padding:30px 50px 0 330px; height:220px; background:#fff url(<?php echo $this->config->item('base_url');?>public/admin/img/20150212154319.jpg) no-repeat 30px 40px; margin:auto; overflow: hidden;}
.login_input{ display:block;width:220px;}
.login_user{ background: url(<?php echo $this->config->item('base_url');?>public/admin/img/input_icon_1.png) no-repeat 200px center; font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif}
.login_password{ background: url(<?php echo $this->config->item('base_url');?>public/admin/img/input_icon_2.png) no-repeat 200px center; font-family:"Courier New", Courier, monospace}
.btn-login{ background:#40454B; box-shadow:none; text-shadow:none; color:#fff; border:none;height:35px; line-height:26px; font-size:14px; font-family:"microsoft yahei";}
.btn-login:hover{ background:#333; color:#fff;}
.copyright{ margin:auto; margin-top:10px; text-align:center; width:370px; color:#CCC}
@media (max-height: 700px) {.tit{ margin:auto; margin-top:100px; }}
@media (max-height: 500px) {.tit{ margin:auto; margin-top:50px; }}
</style>
<script>
	var form_load=0;
	function login_sub()
	{
		var username=$("#username").val();
		var password=$("#password").val();
		//alert(1);
		if(form_load==0)
		{
			if(username=="")
			{
				layer.tips('抱歉：请您输入登录账号!', '#username');
			}
			else if(password=="")
			{
				layer.tips('抱歉：请您输入登录密码!', '#password');	
			}
			else if(password.length<6 || password.length>16)
			{
				layer.tips('抱歉：您输入的登录密码不正确!', '#password');	
			}
			else
			{
				//ajax sends
				var sub_url=$('#login_form').attr('action');
				$.ajax({url:sub_url, 
				type: 'POST', 
				data:{username:username,passwd:password}, 
				dataType: 'html', 
				timeout: 15000, 
					error: function(){
						layer.closeAll();
						form_load=0;
						layer.alert('登录过程出现错误，请您稍后再试！', {
							icon: 7,
							skin: 'layer-ext-moon'
						})						
					},
					beforeSend:function(){
						form_load=1;
						var index = layer.load(1, {
						  shade: [0.5,'#333'] //0.1透明度的白色背景
						});
				
					},
					success:function(result){
						layer.closeAll();
						form_load=0;
						result=result.replace(/(^\s*)|(\s*$)/g,"");
						if(result=="success"){
							location="<?php echo $this->config->item('http_url');?>admin/user/user/index";
						}else{
							layer.alert('您的账号或密码不正确，请您稍后再试！', {
								icon: 2,
								skin: 'layer-ext-moon'
							})								
						}
					} 
				});
			}
		}
		else
		{
				
		}
		
		return false;		
	}
</script>
</head>

<body>
<div class="tit"><img src="<?php echo $this->config->item('base_url');?>public/admin/img/tit.png" alt="" /></div>
<div class="login-wrap">
<form action="<?php echo $this->config->item('http_url');?>admin/login/subs" onsubmit="return login_sub();" name="loginform" accept-charset="utf-8" id="login_form" class="loginForm" method="post">	
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="25" valign="bottom">账  号：</td>
    </tr>
    <tr>
      <td><input type="text" class="login_input login_user" id="username" name="username" value="" /></td>
    </tr>
    <tr>
      <td height="35" valign="bottom">密  码：</td>
    </tr>
    <tr>
      <td><input type="password" id="password" name="password" class="login_input login_password" value="" /></td>
    </tr>
    <tr>
      <td height="60" valign="bottom"><input type="submit" value="登录" style="display:none;" /><a href="javascript:void();" onclick="return login_sub();" class="btn btn-block btn-login">登录</a></td>
    </tr>
   
  </table>
</form>
</div>
<div class="copyright">建议使用IE8以上版本或谷歌浏览器</div>
</body>
</html>
