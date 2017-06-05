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
          <span>其他</span>/
          <span>投诉列表</span>
        </ul>
        <div class="search">
            <?php
                $length = strlen('/index.php/');
                $action = substr($_SERVER['REQUEST_URI'], $length);
            ?>
            <?php echo validation_errors(); ?>
            <?php echo form_open($action, array('method'=>'get')); ?>
                <span>被投诉者昵称:</span>
                <input class="nickname" type="text" name="complainted_nickname" value="<?php echo $params['complainted_nickname'];?>" />
                <span>被投诉者电话:</span>
                <input class="mobile" type="text" name="complainted_mobile" value="<?php echo $params['complainted_mobile'];?>" />
                <span>投诉者昵称:</span>
                <input class="nickname" type="text" name="complaint_nickname" value="<?php echo $params['complaint_nickname'];?>" />
                <span>投诉者电话:</span>
                <input class="mobile" type="text" name="complaint_mobile" value="<?php echo $params['complaint_mobile'];?>" />
                <input type="submit" value="查询" />
            </form>
        </div>
        
        <div class="title_right">
          <div class="text-right">
            <span class="right">投诉总数:</span>&nbsp;&nbsp;<span><?php echo $total_complaints;?></span>
          </div>
          <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th>被投诉者昵称</th>
                    <th>被投诉者电话</th>
                    <th>投诉者昵称</th>
                    <th>投诉者电话</th>
                    <th>原因</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($query as $value):?>
                    <tr align="center">
                        <td><?php echo $value['complainted_nickname']; ?></td>
                        <td><?php echo $value['complainted_mobile']; ?></td>
                        <td><?php echo $value['complaint_nickname']; ?></td>
                        <td><?php echo $value['complaint_mobile']; ?></td>
                        <td><?php echo $value['content']; ?></td>
                        <td>
                            <a class="delete" href="javascript:void(0);">删除</a>
                            <input class="complaint-id" type="hidden" value="<?php echo $value['id'];?>"/>
                        </td>
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
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/user/complaint.js"></script>
<?php
    require VIEWPATH."admin/bottom.php";
?>