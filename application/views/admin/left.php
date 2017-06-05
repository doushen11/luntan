<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/bootstrap/css/bootstrap.min.css" />
<div class="left">
     
<script type="text/javascript">
    var myMenu;
    window.onload = function() {
        myMenu = new SDMenu("my_menu");
        myMenu.init();
    };
</script>
    <div id="my_menu" class="sdmenu">
    
        <div class="collapsed">
            <span>用户管理</span>
            <a href="<?php echo $this->config->item('http_url');?>admin/user/user/index"><label>会员列表</label></a>
            <a href="<?php echo $this->config->item('http_url');?>admin/user/user/disabled"><label>禁用会员</label></a>
        </div>
        <div class="collapsed">
            <span>群管理</span>
            <a href="<?php echo $this->config->item('http_url');?>admin/qun/qun/index"><label>群列表</label></a>
        </div>
        <div class="collapsed">
            <span>论坛管理</span>
            <a href="<?php echo $this->config->item('http_url');?>admin/luntan/luntan/index"><label>论坛列表</label></a>
        </div>
        <div class="collapsed">
            <span>帖子管理</span>
            <a href="<?php echo $this->config->item('http_url');?>admin/luntan/luntan/tiezi"><label>帖子列表</label></a>
        </div>
        <div class="collapsed">
            <span>朋友圈管理</span>
            <a href="<?php echo $this->config->item('http_url');?>admin/circle/circle/index"><label>朋友圈列表</label></a>
        </div>
        <div class="collapsed clearfix">
            <span>权限管理</span>
            <a href="<?php echo $this->config->item('http_url');?>admin/admin/admin/index"><label>管理员列表</label></a>
            <a href="<?php echo $this->config->item('http_url');?>admin/admin/admin/role_index"><label>管理角色</label></a>
        </div>
        <div class="collapsed">
            <span>其他</span>
            <a href="<?php echo $this->config->item('http_url');?>admin/user/user/complaint_list"><label>投诉列表</label></a>
        </div>
    </div>
</div>
<div class="panel-switch"></div>
<script type="text/javascript">
$(document).ready(function(e) {
    $(".panel-switch").click(function(){
        $(".left").toggle();
    });
});
</script>
