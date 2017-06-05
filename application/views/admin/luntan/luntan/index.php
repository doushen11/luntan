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
          <span>论坛管理</span>/
          <span>论坛列表</span>
        </ul>
        <div class="search">
            <?php
                $length = strlen('/index.php/');
                $action = substr($_SERVER['REQUEST_URI'], $length);
            ?>
            <?php echo validation_errors(); ?>
            <?php echo form_open($action, array('method'=>'get')); ?>
                <span>论坛名称:</span>
                <input class="luntan-name" type="text" name="luntan_name" value="<?php echo $params['luntan_name'];?>" />
                <input type="submit" value="查询" />
            </form>
        </div>
        
        <div class="title_right">
          <div class="text-right">
            <span class="right">论坛总数:</span>&nbsp;&nbsp;<span><?php echo $total_luntans;?></span>
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
                    <th>论坛名称</th>
                    <th>论坛图标</th>
                    <th>
                        <a href="<?php echo $this->config->item('http_url') . 'admin/luntan/luntan/index' . $pa.'tiezi_count_order='.$orderby['tiezi_count_order'];?>">发帖量</a>
                        <?php if($current_order == 'tiezi_count'):?>
                            <?php if($orderby['tiezi_count_order'] == 'desc'):?>
                                <span class="glyphicon glyphicon-arrow-up"></span>
                            <?php elseif ($orderby['tiezi_count_order'] == 'asc'):?>
                                <span class="glyphicon glyphicon-arrow-down"></span>
                            <?php endif;?>
                        <?php endif;?>
                    </th>
                    <th>
                        <a href="<?php echo $this->config->item('http_url') . 'admin/luntan/luntan/index' . $pa.'comment_count_order='.$orderby['comment_count_order'];?>">评论量</a>
                        <?php if($current_order == 'comment_count'):?>
                            <?php if($orderby['comment_count_order'] == 'desc'):?>
                                <span class="glyphicon glyphicon-arrow-up"></span>
                            <?php elseif ($orderby['comment_count_order'] == 'asc'):?>
                                <span class="glyphicon glyphicon-arrow-down"></span>
                            <?php endif;?>
                        <?php endif;?>
                    </th>
                    <th>
                        <a href="<?php echo $this->config->item('http_url') . 'admin/luntan/luntan/index' . $pa.'today_see_count_order='.$orderby['today_see_count_order'];?>">今日浏览量</a>
                        <?php if($current_order == 'today_see_count'):?>
                            <?php if($orderby['today_see_count_order'] == 'desc'):?>
                                <span class="glyphicon glyphicon-arrow-up"></span>
                            <?php elseif ($orderby['today_see_count_order'] == 'asc'):?>
                                <span class="glyphicon glyphicon-arrow-down"></span>
                            <?php endif;?>
                        <?php endif;?>
                    </th>
                    <th>
                        <a href="<?php echo $this->config->item('http_url') . 'admin/luntan/luntan/index' . $pa.'focus_count_order='.$orderby['focus_count_order'];?>">关注量</a>
                        <?php if($current_order == 'focus_count'):?>
                            <?php if($orderby['focus_count_order'] == 'desc'):?>
                                <span class="glyphicon glyphicon-arrow-up"></span>
                            <?php elseif ($orderby['focus_count_order'] == 'asc'):?>
                                <span class="glyphicon glyphicon-arrow-down"></span>
                            <?php endif;?>
                        <?php endif;?></th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($query as $value):?>
                    <tr align="center">
                        <td><?php echo $value['luntan_name']; ?></td>
                        <td>
                            <?php if(!empty($value['icon'])):?>
                                <img class="bigger" title="点击放大" alt="" src="<?php echo $this->config->item('base_url').$value['icon']; ?>" />
                            <?php else:?>
                                <img class="bigger" title="点击放大" alt="" src="<?php echo $this->config->item('base_url').'public/picture/placeholder.jpg'; ?>" />
                            <?php endif;?>
                        </td>
                        <td><?php echo $value['tiezi_count']; ?></td>
                        <td><?php echo $value['comment_count']; ?></td>
                        <td><?php echo $value['today_see_count']; ?></td>
                        <td><?php echo $value['focus_count']; ?></td>
                        <td><?php echo $value['create_time']; ?></td>
                        <td>
                            <a href="<?php echo $this->config->item('http_url') . 'admin/luntan/luntan/blocks/' . $value['id'];?>">板块</a>/
                            <a class="delete" href="javascript:void(0);">删除</a>
                            <input class="luntan-id" type="hidden" value="<?php echo $value['id'];?>"/>
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
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/luntan/index.js"></script>
<?php
    require VIEWPATH."admin/bottom.php";
?>