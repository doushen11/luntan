<?php
    require VIEWPATH."admin/main.php";
?>
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
                <a href="<?php ?>admin/line/category">线路管理</a> <span class="divider">/</span>
                <span>线路列表</span>
            </ul>
            <div class="title_right">
                <strong>快捷操作</strong>
            </div>
            <a href="" class="btn btn-info " style="width:80px;margin-bottom: 10px;margin-right:30px;">商家提现管理</a>
            <a href="" class="btn btn-info " style="width:80px;margin-bottom: 10px;margin-right:30px;">到店商家</a>
            <a href="" class="btn btn-info " style="width:80px;margin-bottom: 10px;margin-right:30px;">到家商家</a>
            <a href="" class="btn btn-info " style="width:80px;margin-bottom: 10px;margin-right:30px;">代理列表</a>
            <div class="title_right">
                <strong>系统数据概览</strong>
            </div>
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th width="20%"></th><th width="20%">商家(新增数)</th><th width="20%">订单(新增数)</th><th width="20%">商品(更新数)
                    </th><th width="20%">用户(活跃数)</th>
                </tr>
                <tr align="center">
                    <td><strong>今日</strong></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                </tr>
                <tr align="center">
                    <td><strong>昨日</strong></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                </tr>
                <tr align="center">
                    <td><strong>近七天</strong></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                </tr>
                <tr align="center">
                    <td><strong>近三十天</strong></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                    <td height="39"><?php echo 'ssss'; ?></td>
                </tr>
            </table>
            <div class="title_right">
                <strong>最新加入商家</strong>
            </div>
            <table class="table table-bordered">
                <tr>
                    <th width="20%">商家名</th><th width="20%">电话</th><th width="20%">可提供服务
                    </th><th width="20%">地址</th><th width="20%">注册时间</th>
                </tr>
                <?php foreach($last_join_shop as $v): ?>
                <tr align="center">
                    <td><?php echo htmlspecialchars('ssss'); ?></td>
                    <td><?php echo htmlspecialchars('tttt'); ?></td>
                    <td><?php echo htmlspecialchars('tttt'); ?></td>
                    <td><?php echo htmlspecialchars('tttt'); ?></td>
                    <td><?php echo date('Y-m-d','tttt'); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <div style="margin:40px;"></div>
            <table class="table">
                <tr align="center">
                    <td style="color:#ddd;">
                        中京通达：<a style="color:#ddd;" href="http://www.zjtd100.com" target="_blank">http://www.zjtd100.com（官网）</a>
                        <br>客服电话：4000-465-365
                        <br>版权所有 © 2013-2016 中京通达网络科技有限公司，并保留所有权利
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script>
</script>
</body>
</html>