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
    <div class="right" id="mainFrame">
        <div class="right_cont">
         <ul class="breadcrumb">当前位置：
          <span>朋友圈管理</span>/
          <span>朋友圈列表</span>
        </ul>
        <div class="search">
            <?php
                $length = strlen('/index.php/');
                $action = substr($_SERVER['REQUEST_URI'], $length);
            ?>
            <?php echo validation_errors(); ?>
            <?php echo form_open($action, array('method'=>'get')); ?>
                <span>作者昵称:</span>
                <input class="author-name" type="text" name="nickname" value="<?php echo $params['nickname'];?>" />
                <input type="submit" value="查询" />
            </form>
        </div>
        
        <div class="title_right">
          <div class="text-right">
            <span class="right">朋友圈总数:</span>&nbsp;&nbsp;<span><?php echo $total_circles;?></span>
          </div>
          <?php
            $pa = '';
            if(sizeof($params) > 0) {
                $pa_arr = array();
                foreach ($params as $key=>$value) {
                    if(!empty($value)) {
                        array_push($pa_arr, $key.'='.$value);
                    }
                }
                if (sizeof($pa_arr) > 0) {
                    $pa ='?'. implode('&', $pa_arr).'&';
                } else {
                    $pa = '?';
                }
            } else {
                $pa = '?';
            }
          ?>
          <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th>作者</th>
                    <th>位置</th>
                    <th>内容</th>
                    <th>
                        <a href="<?php echo $this->config->item('http_url') . 'admin/circle/circle/index' . $pa.'thumbs_up_count='.$orderby['thumbs_up_count'];?>">点赞量</a>
                        <?php if($current_order == 'thumbs_up_count'):?>
                            <?php if($orderby['thumbs_up_count'] == 'desc'):?>
                                <span class="glyphicon glyphicon-arrow-up"></span>
                            <?php elseif ($orderby['thumbs_up_count'] == 'asc'):?>
                                <span class="glyphicon glyphicon-arrow-down"></span>
                            <?php endif;?>
                        <?php endif;?>
                    </th>
                    <th>
                        <a href="<?php echo $this->config->item('http_url') . 'admin/circle/circle/index' . $pa.'comment_count='.$orderby['comment_count'];?>">评论量</a>
                        <?php if($current_order == 'comment_count'):?>
                            <?php if($orderby['comment_count'] == 'desc'):?>
                                <span class="glyphicon glyphicon-arrow-up"></span>
                            <?php elseif ($orderby['comment_count'] == 'asc'):?>
                                <span class="glyphicon glyphicon-arrow-down"></span>
                            <?php endif;?>
                        <?php endif;?>
                    </th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($query as $value):?>
                    <tr align="center">
                        <td><?php echo $value['nickname']; ?></td>
                        <td><?php echo $value['location']; ?></td>
                        <td><?php echo $value['content']; ?></td>
                        <td><?php echo $value['thumbs_up_count']; ?></td>
                        <td><?php echo $value['comment_count']; ?></td>
                        <td><?php echo $value['create_time']; ?></td>
                        <td>
                            <a href="<?php echo $this->config->item('http_url') . 'admin/circle/circle/circle_info/' . $value['id'];?>">详情</a>/
                            <a class="circle-delete" href="javascript:void(0);">删除</a>
                            <input class="circle-id" type="hidden" value="<?php echo $value['id'];?>"/>
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
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/circle/index.js"></script>
<?php
    require VIEWPATH."admin/bottom.php";
?>