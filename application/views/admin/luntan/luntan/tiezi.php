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
          <span>帖子管理</span>/
          <span>帖子列表</span>
        </ul>
        <div class="search">
            <?php
                $length = strlen('/index.php/');
                $action = substr($_SERVER['REQUEST_URI'], $length);
            ?>
            <?php echo validation_errors(); ?>
            <?php echo form_open($action, array('method'=>'get')); ?>
                <span>标题:</span>
                <input class="title" type="text" name="title" value="<?php echo $params['title'];?>" />
                <span>作者昵称:</span>
                <input class="author-name" type="text" name="author_name" value="<?php echo $params['author_name'];?>" />
                <span>所属论坛:</span>
                <input class="luntan-name" type="text" name="luntan_name" value="<?php echo $params['luntan_name'];?>" />
                <span>精华帖:</span>
                <select name="is_essence">
                    <option value="">--请选择--</option>
                    <option value="1" <?php if($params['is_essence']==1){ echo "selected";}?>>精华帖</option>
                    <option value="0" <?php if($params['is_essence']==0){ echo "selected";}?>>非精华帖</option>
                </select>
                <input type="submit" value="查询" />
            </form>
        </div>
        
        <div class="title_right">
          <div class="text-right">
            <span class="right">帖子总数:</span>&nbsp;&nbsp;<span><?php echo $total_tiezis;?></span>
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
                    <th>标题</th>
                    <th>作者</th>
                    <th>所属论坛</th>
                    <th>是否精华帖</th>
                    <th>
                        <a href="<?php echo $this->config->item('http_url') . 'admin/luntan/luntan/tiezi' . $pa.'comment_count='.$orderby['comment_count'];?>">评论量</a>
                        <?php if($current_order == 'comment_count'):?>
                            <?php if($orderby['comment_count'] == 'desc'):?>
                                <span class="glyphicon glyphicon-arrow-up"></span>
                            <?php elseif ($orderby['comment_count'] == 'asc'):?>
                                <span class="glyphicon glyphicon-arrow-down"></span>
                            <?php endif;?>
                        <?php endif;?>
                    </th>
                    <th>
                        <a href="<?php echo $this->config->item('http_url') . 'admin/luntan/luntan/tiezi' . $pa.'see_count='.$orderby['see_count'];?>">浏览量</a>
                        <?php if($current_order == 'see_count'):?>
                            <?php if($orderby['see_count'] == 'desc'):?>
                                <span class="glyphicon glyphicon-arrow-up"></span>
                            <?php elseif ($orderby['see_count'] == 'asc'):?>
                                <span class="glyphicon glyphicon-arrow-down"></span>
                            <?php endif;?>
                        <?php endif;?>
                    </th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($query as $value):?>
                    <tr align="center">
                        <td><?php echo $value['title']; ?></td>
                        <td><?php echo $value['nickname']; ?></td>
                        <td><?php echo $value['luntan_name']; ?></td>
                        <td>
                            <?php if($value['is_essence'] == 1):?>
                                <span>是</span>
                            <?php else:?>
                                <span>否</span>
                            <?php endif;?>
                        </td>
                        <td><?php echo $value['comment_count']; ?></td>
                        <td><?php echo $value['see_count']; ?></td>
                        <td><?php echo $value['create_time']; ?></td>
                        <td>
                            <a href="<?php echo $this->config->item('http_url') . 'admin/luntan/luntan/tiezi_info/' . $value['id'];?>">详情</a>/
                            <a class="tiezi-delete" href="javascript:void(0);">删除</a>
                            <input class="tiezi-id" type="hidden" value="<?php echo $value['id'];?>"/>
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
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/luntan/tiezi.js"></script>
<?php
    require VIEWPATH."admin/bottom.php";
?>