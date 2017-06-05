<?php
require VIEWPATH . "admin/main.php";
?>
<link rel="stylesheet"
    href="<?php echo $this->config->item('base_url');?>public/admin/css/pagination.css" />
<!-- 顶部 -->
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
                <span>管理员列表</span>
            </ul>
            <div class="align-right">
                <a class="btn btn-primary" href="<?php echo $this->config->item('http_url') . 'admin/admin/admin/add'?>">添加管理员</a>
            </div>
            <div class="title_right">
                <table
                    class="table table-bordered table-striped table-hover">
                    <tr>
                        <th>管理员账号</th>
                        <th>所属管理组</th>
                        <th>最后登录时间</th>
                        <th>最后登录IP</th>
                        <th>操作</th>
                    </tr>
                <?php foreach ($query as $value):?>
                    <tr align="center">
                        <td><?php echo $value['username']; ?></td>
                        <td><?php echo ($value['group']==0) ? '超级管理员' : $value['name'] ; ?></td>
                        <td><?php echo $value['last_time']; ?></td>
                        <td><?php echo $value['last_ip']; ?></td>
                        <td>
                            <a  class="admin-delete" href="javascript:void(0);">删除</a><span>/</span>
                            <a href="<?php echo $this->config->item('http_url') . 'admin/admin/admin/edit/' . $value['id']?>">编辑</a>
                            <input type="hidden" value="<?php echo $value['id'];?>" class="admin-id"/>
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