<?php
	require VIEWPATH."admin/main.php";
?>
<!-- 顶部 -->
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/css/pagination.css" />
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/css/main.css" />

<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>public/admin/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css" />

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
          <span>群管理</span>/
          <span>群列表</span>
        </ul>
        <div class="search">
            <?php
                $length = strlen('/index.php/');
                $action = substr($_SERVER['REQUEST_URI'], $length);
            ?>
            <?php echo validation_errors(); ?>
            <?php echo form_open($action, array('method'=>'get')); ?>
                <div class="search-date">
                    <span>创建时间:</span>
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
                        <input class="form-control" size="16" type="text" value="<?php echo $params['from_date'];?>" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input1" value="<?php echo $params['from_date'];?>" name="from_date"/><br/>
                </div>
                <div class="search-date">
                    <span>到:</span>
                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                        <input class="form-control" size="16" type="text" value="<?php echo $params['end_date'];?>" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input2" value="<?php echo $params['end_date'];?>" name="end_date"/><br/>
                </div>
                <span>群名称:</span>
                <input class="group-name" type="text" name="group_name" value="<?php echo $params['group_name'];?>" />
                <span>成员昵称:</span>
                <input class="owner-name" type="text" name="owner_name" value="<?php echo $params['owner_name'];?>" />
                <span>成员手机号:</span>
                <input class="owner-mobile" type="text" name="owner_mobile" value="<?php echo $params['owner_mobile'];?>" />
                <input type="submit" value="查询" />
            </form>
        </div>
        
        <div class="title_right">
          <div class="text-right">
            <span class="right">群总数:</span>&nbsp;&nbsp;<span><?php echo $total_quns;?></span>
          </div>
          <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th>群名称</th>
                    <th>最大人数</th>
                    <th>现有人数</th>
                    <th>群主昵称</th>
                    <th>群主手机号</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                <?php foreach ($query as $value):?>
                    <tr align="center">
                        <td><?php echo $value['group_name']; ?></td>
                        <td><?php echo $value['maxusers']; ?></td>
                        <td><?php echo $value['currentusers']; ?></td>
                        <td><?php echo $value['nickname']; ?></td>
                        <td><?php echo $value['mobile']; ?></td>
                        <td><?php echo $value['create_time']; ?></td>
                        <td>
                            <a href="<?php echo $this->config->item('http_url') . 'admin/qun/qun/members/' . $value['id'];?>">查看成员</a>/
                            <a class="delete-qun" href="javascript:void(0);">删除</a>
                            <input class="qun-id" type="hidden" value="<?php echo $value['id'];?>"/>
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
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/qun/index.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">
    $('.form_date').datetimepicker({
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
</script>
<?php
    require VIEWPATH."admin/bottom.php";
?>