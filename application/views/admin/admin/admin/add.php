<!-- 顶部 -->
<?php
	require VIEWPATH."admin/top.php";
?>
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/css/pagination.css" />
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/css/main.css" />
<?php
require VIEWPATH."admin/main.php";
?>
<div id="middle">
<?php
    require VIEWPATH."admin/left.php";
?>
    <div class="right"  id="mainFrame">
        <div class="right_cont">
            <div class="breadcrumb">当前位置：
                <a href="<?php echo $this->config->item('http_url');?>admin/admin/admin/index">管理员列表</a>/
                <span>添加管理员</span>
            </div>
            <div class="info-edit">
                <div class="info-block">
                    <span>登录账号:</span>
                    <input type="text" name="username" value="<?php echo set_value('username'); ?>"/>
                    <span class="error-info"><?php echo form_error('username'); ?></span>
                </div>
                <div class="info-block">
                    <span>登录密码:</span>
                    <input type="text" name="passwd" value="<?php echo set_value('"passwd"'); ?>"/>
                    <span class="error-info"><?php echo form_error('"passwd"'); ?></span>
                </div>
                <div class="info-block">
                    <span>对应角色:</span>
                    <select name="role">
                        <option value="">--请选择--</option>
                        <?php foreach ($roles as $role):?>
                            <option value="<?php echo $role['id'];?>"><?php echo $role['name'];?></option>
                        <?php endforeach;?>
                    </select>
                    <span class="error-info"><?php echo form_error('sex'); ?></span>
                </div>
                <div class="submit-block">
                    <input id="admin-add-submit" class="btn btn-info submit" type="button" value="保存" />
                    <input class="btn btn-info back" type="button" value="返回" />
                    <input type="hidden" name="type" value="add" />
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