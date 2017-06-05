<?php
require VIEWPATH . "admin/main.php";
?>
<!-- 顶部 -->
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/css/pagination.css" />
<?php
require VIEWPATH . "admin/top.php";
?>
<div id="middle">
<?php
require VIEWPATH . "admin/left.php";
?>
<div class="right" id="mainFrame">
    <div class="right_cont">
        <ul class="breadcrumb">
            <span>当前位置：</span>
            <span>权限管理</span>/
            <span>角色管理</span>
        </ul>
        <div class="align-right">
            <a class="btn btn-primary" href="<?php echo $this->config->item('http_url') . 'admin/admin/admin/role_add';?>">添加角色</a>
        </div>
        <div class="title_right">
            <table
                class="table table-bordered table-striped table-hover">
                <tr>
                    <th>角色名称</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($query as $value):?>
                    <tr align="center">
                        <td><?php echo $value['name']; ?></td>
                        <td>
                            <a class="role-delete" href="javascript:void(0);">删除</a><span>/</span>
                            <a href="<?php echo $this->config->item('http_url') . 'admin/admin/admin/role_edit/' . $value['id'];?>">编辑</a>
                            <input type="hidden" value="<?php echo $value['id'];?>" class="role-id"/>
                        </td>
                    </tr>
                <?php endforeach;?>
            </table>
        </div>
    </div>
</div>
</div>
<!-- 底部 -->
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/admin/index.js"></script>
<?php
    require VIEWPATH."admin/bottom.php";
?>