$(function(){
    function validate_empty(username, passwd, role) {
        if(username == undefined || username == '') {
            layer.msg('登录账号不能为空');
            return false;
        }
        if(passwd == undefined || passwd == '') {
            layer.msg('密码不能为空');
            return false;
        }
        if(role == undefined || role == '') {
            layer.msg('角色不能为空');
            return false;
        }
        return true;
    }
    
    $('.role-delete').click(function(){
        var params = {};
        params['role_id'] = $(this).siblings('input.role-id').val();
        var parent = $(this).parents('tr');
        
        layer.confirm('确定删除吗?', {
            btn: ['关闭','确定'] //按钮
        }, function(index){
            layer.close(index);
        }, function(){
            $.getJSON(http_url+'admin/admin/admin/role_delete',params,function(data,status){
                if(data.code == 10000){
                    parent.remove();
                    layer.msg('成功');
                } else if(data.code == 20000) {
                    location.href = http_url+'admin/login/destory';
                } else if(data.code == 40000) {
                    layer.msg('该角色下存在管理员,不能删除');
                } else {
                    layer.msg('删除失败');
                }
            });
        });
    });
    
    $('.admin-delete').click(function(){
        var params = {};
        params['admin_id'] = $(this).siblings('input.admin-id').val();
        var parent = $(this).parents('tr');
        
        layer.confirm('确定删除吗?', {
            btn: ['关闭','确定'] //按钮
        }, function(index){
            layer.close(index);
        }, function(){
            $.getJSON(http_url+'admin/admin/admin/delete',params,function(data,status){
                if(data.code == 10000){
                    parent.remove();
                    layer.msg('成功');
                } else if(data.code == 20000) {
                    location.href = http_url+'admin/login/destory';
                } else {
                    layer.msg('删除失败');
                }
            });
        });
    });
    
    $('#admin-add-submit').click(function(){
        var username = $.trim($('input[name="username"]').val());
        var passwd = $.trim($('input[name="passwd"]').val());
        var role = $.trim($('select[name="role"]').val());
        if(validate_empty(username, passwd, role)) {
            layer.load(0, {shade: true});
            $.ajax({url:http_url+'admin/admin/admin/add',
                type: 'POST',
                data:{username:username,passwd:passwd,role:role},
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
                        }else if(result.code == 40000){
                            layer.closeAll();
                            layer.alert('处理失败，登录账号已存在！', {
                                icon: 2,
                                skin: 'layer-ext-moon'
                            })
                        }else if(result.code == 10000){
                            layer.msg('添加成功');
                            location=http_url+'admin/admin/admin/index';
                        }else{
                            layer.alert('处理失败，请您稍后再试！', {
                                icon: 7,
                                skin: 'layer-ext-moon'
                            })
                        }
                    }
                });
        }
    });
    
    $('#admin-edit-submit').click(function(){
        var username = $.trim($('input[name="username"]').val());
        var passwd = $.trim($('input[name="passwd"]').val());
        var role = $.trim($('select[name="role"]').val());
        var admin_id = $.trim($('input.admin-id').val());
        
        if(validate_empty(username, passwd, role)) {
            layer.load(0, {shade: true});
            $.ajax({url:http_url+'admin/admin/admin/edit',
                type: 'POST',
                data:{admin_id:admin_id,passwd:passwd,role:role},
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
                        }else if(result.code == 10000){
                            layer.msg('修改成功');
                            location=http_url+'admin/admin/admin/index';
                        }else{
                            layer.alert('处理失败，请您稍后再试！', {
                                icon: 7,
                                skin: 'layer-ext-moon'
                            })
                        }
                    }
                });
        }
    });
    
    $('.back').click(function(){
        location = http_url + 'admin/admin/admin/index';
    });
});