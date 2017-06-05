<?php
require VIEWPATH."admin/main.php";
?>
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/css/pagination.css" />
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/css/main.css" />
<!-- 顶部 -->
<?php
	require VIEWPATH."admin/top.php";
?>
<div id="middle">
<?php
    require VIEWPATH."admin/left.php";
?>
    <div class="right"  id="mainFrame">
        <div class="right_cont">
            <div class="breadcrumb">当前位置：
                <a href="<?php echo $this->config->item('http_url');?>admin/admin/admin/index">管理员列表</a>/
                <span>修改管理员</span>
            </div>
            <div class="info-edit">
                <div class="info-block">
                    <span>登录账号:</span>
                    <input type="text" name="username" value="<?php echo $query['username']; ?>" readonly/>
                </div>
                <div class="info-block">
                    <span>登录密码:</span>
                    <input type="text" name="passwd" value="<?php echo set_value('"passwd"'); ?>"/>
                </div>
                <div class="info-block">
                    <span>对应角色:</span>
                    <select name="role">
                        <option value="">--请选择--</option>
                        <?php foreach ($roles as $role):?>
                            <option value="<?php echo $role['id'];?>" <?php if($query['group'] == $role['id']){echo 'selected';}?>><?php echo $role['name'];?></option>
                        <?php endforeach;?>
                    </select>
                    <span class="error-info"><?php echo form_error('sex'); ?></span>
                </div>
                <div class="submit-block">
                    <input id="admin-edit-submit" class="btn btn-info submit" type="button" value="保存" />
                    <input class="btn btn-info back" type="button" value="返回" />
                    <input type="hidden" name="type" value="add" />
                    <input type="hidden" class="admin-id" value="<?php echo $query['id'];?>" />
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 底部 -->
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/admin/index.js"></script>
<?php
    require VIEWPATH."admin/bottom.php";
?>