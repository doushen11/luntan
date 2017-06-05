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
                <a href="<?php echo $this->config->item('http_url');?>admin/luntan/luntan/tiezi">帖子列表</a>/
                <span>帖子详情</span>
            </div>
            <table class="table table-bordered table-striped table-hover">
                    <tr>
                        <th>标题</th>
                        <th>是否精华帖</th>
                        <th>评论量</th>
                        <th>浏览量</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    <tr align="center">
                        <td><?php echo $tiezi['tiezi']['title']; ?></td>
                        <td>
                            <?php if($tiezi['tiezi']['is_essence'] == 1):?>
                                <span>是</span>
                            <?php else:?>
                                <span>否</span>
                            <?php endif;?>
                        </td>
                        <td><?php echo $tiezi['tiezi']['comment_count']; ?></td>
                        <td><?php echo $tiezi['tiezi']['see_count']; ?></td>
                        <td><?php echo $tiezi['tiezi']['create_time']; ?></td>
                        <td>
                            <?php if($tiezi['tiezi']['is_essence'] == 1):?>
                                <a class="not-essence" href="javascript:void(0);">非精华</a>/
                            <?php else:?>
                                <a class="is-essence" href="javascript:void(0);">精华</a>/
                            <?php endif;?>
                            <a class="tiezi-delete" href="javascript:void(0);">删除</a>
                            <input class="tiezi-id" type="hidden" value="<?php echo $tiezi['tiezi']['id'];?>"/>
                        </td>
                    </tr>
            </table>
            <div>
                <span>内容:</span>
                <p class="tiezi-content"><?php echo $tiezi['tiezi']['content'];?></p>
            </div>
            <?php if($tiezi['tiezi']['has_img'] == 1):?>
                <div class="tiezi-imgs">
                    <span>图片:</span>
                    <?php foreach ($tiezi['imgs'] as $img):?>
                        <img alt="" src="<?php echo base_url().$img['url'];?>" />
                    <?php endforeach;?>
                </div>
            <?php elseif ($tiezi['tiezi']['has_img'] == 2):?>
                <div class="tiezi-video">
                    <span>视频:</span>
                    <div>
                        <img alt="" src="<?php echo base_url().$tiezi['video'][0]['thumbnail_url'];?>" />
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
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/luntan/tiezi.js"></script>