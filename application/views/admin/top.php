<script>  
    function checkhHtml5()   
    {   
        if(typeof(Worker)!=="undefined")   
        {   
          
        }
		else   
        {   
		 layer.open({
		  type: 1,
		  skin: 'layui-layer-demo', //样式类名
		  closeBtn: 0, //不显示关闭按钮
		  shift: 2,
		  shadeClose:false, //开启遮罩关闭
		  content: '<span style="padding:10px 10px 30px 10px;float:left;font-size:14px;line-height:30px;">抱歉：您的浏览器不支持HTML5等相关功能属性！请先下载安装高版本浏览器使用！<font color="#cc0000">如果您使用的是360，搜狗等浏览器，请检测您的浏览器运行模式是否是”极速模式“，如果不是请切换到极速模式！</font></span>'
		});          
        }  
    }
	$(document).ready(function() {
		checkhHtml5();
        $('#modal-logout').click(function(){
    		layer.confirm('您确定要注销退出系统吗？', {
    		    btn: ['关闭','确定退出'] //按钮
    		  }, function(index){
    		      layer.close(index);
    		  }, function(){
                   $.getJSON(http_url+'admin/login/logout',function(data,status){
                       if(data.code == 10000){
                           window.location.href = http_url + 'admin/login/index';
                       } else {
                           layer.msg('退出失败');
                       }
                   });
    		  });
        });
	});

    
</script>
<div class="header">
	 <div class="logo">
        <img  src="<?php echo $this->config->item('base_url');?>public/admin/img/logo.png" />
     </div>
	<div class="header-right">
        <i class="icon-off icon-white"></i>
        <a id="modal-logout" href="#modal-container-973558" role="button" data-toggle="modal">注销</a>
        <i class="icon-user icon-white"></i>
        <a href="#"><?php echo $rs['username'];?></a>
	</div>
</div>