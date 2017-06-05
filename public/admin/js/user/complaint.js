$(function(){
    $('.delete').click(function(){
        var complaintId = $(this).siblings('.complaint-id').val();
        var parent = $(this).parents('tr');
        layer.confirm('确定删除吗?', {
            btn: ['关闭','确定'] //按钮
        }, function(index){
            layer.close(index);
        }, function(){
            layer.load(0, {shade: true});
            $.ajax({url:http_url+'admin/user/user/complaint_delete',
                type: 'POST',
                data:{complaint_id:complaintId},
                dataType: 'json',
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
                    if(result.code == 20000){
                        location=http_url+'admin/login/destory';
                    }else if(result.code == 30000){
                        layer.closeAll();
                        layer.alert('处理失败，请您稍后再试', {
                            icon: 2,
                            skin: 'layer-ext-moon'
                        })
                    }else if(result.code == 10000){
                        layer.msg('处理成功');
                        parent.remove();
                    }else{
                        layer.alert('处理失败，请您稍后再试！', {
                            icon: 7,
                            skin: 'layer-ext-moon'
                        })
                    }
                }
            });
        });
    });
});