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
    <div class="right" id="mainFrame">
        <div class="right_cont">
            <div class="breadcrumb">当前位置：
                <a href="<?php echo $this->config->item('http_url');?>admin/circle/circle/index">朋友圈列表</a>/
                <span>朋友圈详情</span>
            </div>
            <table class="table table-bordered table-striped table-hover">
                    <tr>
                        <th>作者</th>
                        <th>位置</th>
                        <th>点赞量</th>
                        <th>评论量</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    <tr align="center">
                        <td><?php echo $circle['circle']['nickname']; ?></td>
                        <td><?php echo $circle['circle']['location']; ?></td>
                        <td><?php echo $circle['circle']['thumbs_up_count']; ?></td>
                        <td><?php echo $circle['circle']['comment_count']; ?></td>
                        <td><?php echo $circle['circle']['create_time']; ?></td>
                        <td>
                            <a class="circle-delete" href="javascript:void(0);">删除</a>
                            <input class="circle-id" type="hidden" value="<?php echo $circle['circle']['id'];?>"/>
                        </td>
                    </tr>
            </table>
            <div>
                <span>内容:</span>
                <p class="circle-content"><?php echo $circle['circle']['content'];?></p>
            </div>
            <?php if(sizeof($circle['imgs']) > 0):?>
                <div class="circle-imgs">
                    <span>图片:</span>
                    <?php foreach ($circle['imgs'] as $img):?>
                        <img alt="" src="<?php echo base_url().$img['url'];?>" />
                    <?php endforeach;?>
                </div>
            <?php elseif (sizeof($circle['video']) > 0):?>
                <div class="circle-video">
                    <span>视频:</span>
                    <div>
                        <img alt="" src="<?php echo base_url().$circle['video'][0]['thumbnail_url'];?>" />
                    </div>
                </div>
            <?php endif;?>
        </div>
    </div>
    
</div>
<!-- 底部 -->
<?php
    require VIEWPATH."admin/bottom.php";
?>

<script>
    $(function(){
        var imgs = $('.right_cont').find('img');
        imgs.each(function(){
            $(this).width(100);
            $(this).height(100);
        });
    });
</script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/circle/index.js"></script>