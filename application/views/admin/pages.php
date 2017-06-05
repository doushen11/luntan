<?php
    $str_param = '?';
    foreach ($params as $key=>$value) {
        if(isset($value) && $value!='') {
            $str_param = $str_param . $key .'=' . $value . '&';
        }
    }
?>
<script type="text/javascript">
    function page_go(num){
        if(num=="" || isNaN(num)){
            layer.alert('请您输入要跳转的正确页码数！', {
                icon: 7,
                skin: 'layer-ext-moon'
            })
        }else{
            <?php if(strlen($str_param)>1):?>
                location="<?php echo $this->config->item('http_url') . $url. $str_param?>page="+num;
            <?php else:?>
                location="<?php echo $this->config->item('http_url') . $url;?>?page="+num;
            <?php endif;?>
        }
    }
</script>

<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/admin/js/jquery.page.js"></script>
<div class="page" style="width:60%;"></div>
<script type="text/javascript">
    //最简单的分页，一切默认
    $('.page').createPage(function(n){
    },{
        pageCount:<?php echo $pageall;?>,//总页码,默认10
        current:<?php echo $pageindex;?>,//当前页码,默认1
    },{
		"fontSize":12,//字体大小
		"width":80,//页码盒子总宽度
		"height":30,//页码总高度，默认20px
		"pagesMargin":5,//每个页码或按钮之间的间隔
		"paddL":0,//左边留白
		"paddR":0,//右边留白
		"borderColor":"#428bca",//边线颜色
		"currentColor":"#ed601b",//当前页码的字体颜色
		"disableColor":"#bfbfbf",//不可点击按钮的字体颜色
		"disableBackColor":"#f2f2f2",//不可点击按钮的背景色
		"prevNextWidth":48,//上页下页的宽度
		"pagecountWidth":48,//共计多少页的宽度
        "trunWidth":140//跳转模块宽度
    });
    var tiaoPage = 1;
    // 由于每次点击跳转的时候都把input里面的内容给清掉了，所有后来拿不到
    $('.countYe').on('change', 'input' , function() {
        tiaoPage = $('.countYe').children('input').first().val();
        console.log(tiaoPage);
    });
    var maxPage = <?php echo $pageall;?>;
    $('.page').on('click', 'a' , function() {
        if($(this).text() == '<上一页') {
            page_go(<?php echo $pageindex-1;?>);
        } else if($(this).text() == '下一页>') {
            page_go(<?php echo $pageindex+1;?>);
        } else if($(this).text() == '确定'){
            if(tiaoPage > maxPage) {
                page_go(maxPage);
            } else {
                page_go(tiaoPage);
            }
        } else {
            page_go($(this).text());
        }
    });
</script>