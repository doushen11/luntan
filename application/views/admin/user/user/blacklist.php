<?php
	require VIEWPATH."admin/main.php";
?>
<!-- 顶部 -->
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/css/pagination.css" />
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/css/main.css" />

<?php
	require VIEWPATH."admin/top.php";
?>
<div id="middle">
<?php
    require VIEWPATH."admin/left.php";
?>
    <div class="right"  id="mainFrame">
        <div class="right_cont">
         <ul class="breadcrumb">当前位置：
          <span>用户管理</span>/
          <span>黑名单列表</span>
        </ul>
        <div class="search">
            <?php
                $length = strlen('/index.php/');
                $action = substr($_SERVER['REQUEST_URI'], $length);
            ?>
            <?php echo validation_errors(); ?>
            <?php echo form_open($action, array('method'=>'get')); ?>
                <span>手机号:</span>
                <input class="mobile" type="text" name="mobile" value="<?php echo $params['mobile'];?>" />
                <span>昵称:</span>
                <input class="nickname" type="text" name="nickname" value="<?php echo $params['nickname'];?>" />
                <input type="submit" value="查询" />
            </form>
        </div>
        
        <div class="title_right">
          <div class="text-right">
            <span class="right">黑名单总数:</span>&nbsp;&nbsp;<span><?php echo $total_users;?></span>
          </div>
          <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th>照片</th>
                    <th>昵称</th>
                    <th>手机号</th>
                    <th>性别</th>
                </tr>
                <?php foreach ($query as $value):?>
                    <tr align="center">
                        <td>
                            <?php if(!empty($value['blacklist_photo'])):?>
                                <img class="bigger" title="点击放大" alt="" src="<?php echo $this->config->item('base_url').$value['blacklist_photo']; ?>" />
                            <?php else:?>
                                <img class="bigger" title="点击放大" alt="" src="<?php echo $this->config->item('base_url').'public/picture/user_photo.jpg'; ?>" />
                            <?php endif;?>
                        </td>
                        <td><?php echo $value['blacklist_nickname']; ?></td>
                        <td><?php echo $value['blacklist_mobile']; ?></td>
                        <td><?php echo $value['blacklist_sex']; ?></td>
                    </tr>
                <?php endforeach;?>
        </table>
        <?php
            require VIEWPATH."admin/pages.php";
        ?>
    </div>
</div>
</div>
</div>
<!-- 底部 -->
<?php
    require VIEWPATH."admin/bottom.php";
?>