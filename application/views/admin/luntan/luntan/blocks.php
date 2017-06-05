<?php
	require VIEWPATH."admin/main.php";
?>
<!-- 顶部 -->
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/css/main.css" />

<?php
	require VIEWPATH."admin/top.php";
?>
<div id="middle">
<?php
    require VIEWPATH."admin/left.php";
?>
    <input class="luntan-id" type="hidden" value="<?php echo $this->uri->segment(5);?>" />
    <div class="right"  id="mainFrame">
        <div class="right_cont">
            <ul class="breadcrumb">当前位置：
              <a href="<?php echo $this->config->item('http_url') . 'admin/luntan/luntan/index';?>">论坛列表</a>/
              <span>板块列表</span>
            </ul>
            <div class="align-right">
                <a class="block-add btn btn-primary" href="javascript:void(0);">添加板块</a>
            </div>
            <?php foreach ($blocks as $block):?>
                <div class="luntan-block">
                    <div class="block-title"><?php echo $block['block_name'];?></div>
                    <span class="glyphicon glyphicon-remove block-remove"></span>
                    <input class="luntan-block-id" type="hidden" value="<?php echo $block['id'];?>" />
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
<!-- 底部 -->
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/luntan/index.js"></script>
<?php
    require VIEWPATH."admin/bottom.php";
?>