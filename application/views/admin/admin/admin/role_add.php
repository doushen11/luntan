<?php
require VIEWPATH."admin/main.php";
?>
<script>
    function s(id)
    {
        var s_group = document.getElementsByName("rs_" + id);
        var s_group_value="";
        for(var i = 0; i< s_group.length; i++)
        {
            if(s_group[i].checked==true){
                if(s_group_value==""){
                    s_group_value=s_group[i].value;
                }else{
                    s_group_value=s_group_value + "," + s_group[i].value;
                }
            }
        }
        var s_group_list = document.getElementsByClassName("sx_" + id);

        for(var i = 0; i< s_group_list.length; i++)
        {
            s_group_list[i].checked=false;
            
            if(s_group_value=="recson")
            {
                s_group_list[i].checked=true;
            }
        }
    }
    
    function func_name()
    {
        var al=document.getElementsByName("levels");
        var al_value="";
        for(var i = 0; i< al.length; i++)
        {
            if(al[i].checked==true){
                if(al_value==""){
                    al_value="{" + al[i].value + "}";
                }else{
                    al_value=al_value + ",{" + al[i].value + "}";
                }
            }
        }
        return al_value;
    }
    
    function adds()
    {
        var controller_name=$("#controller_name").val().replace(/(^\s*)|(\s*$)/g,"");
        var func_name_value=func_name();
        //alert(func_name_value);
        if(controller_name=="")
        {
            layer.alert('抱歉：请填写角色名称', {
              icon: 7,
              skin: 'layer-ext-moon'
            })
        }
        else if(func_name_value=="")
        {
            layer.alert('抱歉：请至少选择一项权限操作功能', {
              icon: 7,
              skin: 'layer-ext-moon'
            })
        }
        else
        {
            var sub_url=$('#form2').attr('action');
            $.ajax({url:sub_url, 
            type: 'POST', 
            data:{controller_name:controller_name,func_name_value:func_name_value},
            dataType: 'html', 
            timeout: 15000, 
                error: function(){
                    layer.closeAll();
                    layer.alert('处理失败，请您稍后再试！', {
                        icon: 7,
                        skin: 'layer-ext-moon'
                    })
                },
                beforeSend:function(){
                    layer.closeAll();
                    var index = layer.load(3,{
                        shade: [0.2,'#333333'] //0.1透明度的白色背景
                    });
                },
                success:function(result){
                    layer.closeAll();
                    var result = eval("("+result+")");
                    console.log(result.code);

                    if(result.code == 20000){
                        location='<?php echo base_url().'index.php/';?>admin/login/destory';
                    }else if(result.code == 40000){
                        layer.closeAll();
                        layer.alert('处理失败，角色名称已经被占用！', {
                            icon: 2,
                            skin: 'layer-ext-moon'
                        })
                    }else if(result.code == 10000){
                        layer.msg('添加成功');
                        location='<?php echo base_url().'index.php/';?>admin/admin/admin/role_index';
                    }else{
                        layer.alert('处理失败，请您稍后再试！', {
                            icon: 7,
                            skin: 'layer-ext-moon'
                        })
                    }
                } 
            });
        }
        
        return false;
    }
</script>
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
  <a href="<?php echo base_url().'index.php/';?>admin/admin/admin/role_index">角色管理</a> <span class="divider">/</span>
  角色添加
</ul>
   <div style="width:900px; margin:auto">
   <form id="form2" name="form2" method="post" action="<?php echo base_url().'index.php/';?>admin/admin/admin/role_add" onSubmit="return adds();">
       <table class="table table-bordered">
         <tr>
     <td width="10%" height="50" align="right" bgcolor="#f1f1f1">角色名称：</td>
     <td height="50"><div style="width:20%;float:left;padding-left:1%;"><input type="text" name="controller_name" id="controller_name" class="span1-11" /></div></td>
     </tr>
     <tr>
       <td height="50" align="right" bgcolor="#f1f1f1">拥有权限：</td>
       <td height="50">
       <div class="recson">
               <?php
                $a=1;
                foreach($all_permissions->result_array() as $array){
            ?>
            <div class="recson_1"><span><input type="checkbox" name="rs_<?php echo $a;?>" value="recson" onclick="s('<?php echo $a;?>');" /></span><?php $arr1=explode(":",$array["controller"]);echo $arr1[1];?></div>
            <div class="recson_2">
            <ul>
                <?php
                    $arr2=explode(",",$array["function"]);
                    foreach($arr2 as $k=>$v)
                    {
                        $arr3=explode(":",$v);
                ?>
                <li>
                    <span><input type="checkbox" class="sx_<?php echo $a;?>" id="sx_<?php echo $a;?>" name="levels" value="<?php echo $arr3[0];?>" /></span> <?php echo $arr3[1];?>
                </li>
                <?php
                    }
                ?>
            </ul>
            </div>
            <?php
                    $a++;
                }
            ?>
       </div>
       </td>
     </tr>
     </table>
       <table  class="margin-bottom-20 table  no-border" >
        <tr>
         <td class="text-center"><input type="submit" value=" 添 加 " class="btn btn-info " style="width:80px;" /></td>
     </tr>
 </table>
 </form>
   </div>
     </div>
     </div>
    </div>
<?php
    require VIEWPATH."admin/bottom.php";
?>