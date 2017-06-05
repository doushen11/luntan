$(function(){
//    $('.luntan-block').mouseover(function(){
//        $(this).children('.block-remove').show();
//    });
//    $('.luntan-block').mouseout(function(){
//        $(this).children('.block-remove').hide();
//    });

    $('.right_cont').on('mouseover', '.luntan-block', function(){
        console.log('sss');
        $(this).children('.block-remove').show();
    });
    $('.right_cont').on('mouseout', '.luntan-block', function(){
        console.log('ttt');
        $(this).children('.block-remove').hide();
    });

    $('.right_cont').on('click', '.block-remove', function(){
        var luntanBlockId = $(this).siblings('.luntan-block-id').val();
        var parent = $(this).parents('.luntan-block');
        layer.confirm('确定删除吗?', {
            btn: ['关闭','确定'] //按钮
        }, function(index){
            layer.close(index);
        }, function(){
            layer.load(0, {shade: true});
            $.ajax({url:http_url+'admin/luntan/luntan/block_delete',
                type: 'POST',
                data:{luntan_block_id:luntanBlockId},
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

    $('.block-add').click(function(){
        //prompt层
        layer.prompt({title: '输入板块名称，并确认', formType: 0}, function(pass, index){
          layer.close(index);
          layer.load(0, {shade: true});
          var luntanId = $('.luntan-id').val();
          $.ajax({url:http_url+'admin/luntan/luntan/block_add',
              type: 'POST',
              data:{luntan_id:luntanId, block_name:pass},
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
                      var luntanBlockId = result.resultCode.luntan_block_id;
                      $('.right_cont').append('<div class="luntan-block">'+
                              '<div class="block-title">'+pass+'</div>'+
                              '<span class="glyphicon glyphicon-remove block-remove"></span>'+
                              '<input class="luntan-block-id" type="hidden" value="'+luntanBlockId+'" />'+
                          '</div>');
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

    $('.delete').on('click', function(){
        var luntanId = $(this).siblings('.luntan-id').val();
        console.log(luntanId);
        layer.confirm('确定删除吗?', {
            btn: ['关闭','确定'] //按钮
        }, function(index){
            layer.close(index);
        }, function(){
            layer.load(0, {shade: true});
            $.ajax({url:http_url+'admin/luntan/luntan/delete',
                type: 'POST',
                data:{luntan_id:luntanId},
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
                        location=http_url+'admin/luntan/luntan/index';
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