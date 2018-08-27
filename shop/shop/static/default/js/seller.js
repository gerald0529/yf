$(function () {
    $('label.del a').click(function () {
        var e = $(this);
        var data_str = e.attr('data-param');
        eval("data_str = " + data_str);
        var length = $('.checkitem:checked').length;
        if (length > 0) {
            var chk_value = [];//定义一个数组
            $("input[name='chk[]']:checked").each(function () {
                chk_value.push($(this).val());//将选中的值添加到数组chk_value中
            });
            $.dialog.confirm('您确定要删除吗?', function () {
                $.post(SITE_URL + '?ctl=' + data_str.ctl + '&met=' + data_str.met + '&typ=json', {id: chk_value}, function (data) {
                    console.log(data);
                    if (data && 200 == data.status) {
                        //$.dialog.alert('删除成功',function(){location.reload();});
                        Public.tips.success(data.msg);
                        location.reload();
                    } else {
                        //$.dialog.alert('删除失败');
                        Public.tips.error(data.msg);
                    }
                });
            });
        } else {
            $.dialog.alert('请选择需要操作的记录');
        }
    });
    
    $('span.del a').click(function () {
        if ($(this).attr("data-dis")) {
            return 0;
        }
        var e = $(this);
        var data_str = e.attr('data-param');
        eval("data_str = " + data_str);
        id = data_str.id;
        var chk_value = [];//定义一个数组
        chk_value.push(id);
        $.dialog.confirm('您确定要删除吗?', function () {
            $.post(SITE_URL + '?ctl=' + data_str.ctl + '&met=' + data_str.met + '&typ=json', {id: chk_value}, function (data) {
                console.log(data);
                if (data && 200 == data.status) {
                    //$.dialog.alert('删除成功',function(){location.reload();});
                    Public.tips.success('删除成功!');
                    //Public.tips.success(data.msg);
                    location.reload();
                } else {
                    var msg = data.msg?data.msg:"删除失败！";
                    //$.dialog.alert('删除失败');
                    Public.tips.error(msg); 
                }             
            });
            // var url = data_str.act =='undefined' ? SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json' : SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json&act='+data_str.act
            // console.log(url);
            //    $.post(url,{id:data_str.id},function(data)
            // {
            // 	//alert(JSON.stringify(data));
            // 	if(data && 200 == data.status) {
            //                  Public.tips({ content: "删除成功！"});
            //                  $('#tr_common_id_'+data_str.id).hide();
            //                  setTimeout(function(){
            //                  	e.parents('tr').hide('slow', function() {
            // 				var row_count = $('#table_list').find('.row_line:visible').length;
            // 				if(row_count <= 0)
            // 				{
            // 					$('#list_norecord').show();
            // 				}
            // 			});
            //                  },200);
            // 	} else {
            // 		// showError(data.message);
            // 		//$.dialog.alert('删除失败!');
            // 		Public.tips({type: 1, content: "删除失败！"});
            // 	}
            // });
        });
    });
    
    $('#table_list').on('click', 'span.del a', function () {
        var e = $(this);
        var data_str = e.attr('data-param');
        eval("data_str = " + data_str);
        $.dialog.confirm('您确定要删除吗?', function () {
            $.post(SITE_URL + '?ctl=' + data_str.ctl + '&met=' + data_str.met + '&typ=json', {id: data_str.id}, function (data) {
                //alert(JSON.stringify(data));
                if (data && 200 == data.status) {
                    e.parents('tr').hide('slow', function () {
                        var row_count = $('#table_list').find('.row_line:visible').length;
                        if (row_count <= 0) {
                            $('#list_norecord').show();
                        }
                    });
                } else {
                    // showError(data.message);
                    //$.dialog.alert('删除失败!');
                    Public.tips({type: 1, content: "删除失败！"});
                }
            });
        });
    });
    
    $('.checkall').click(function () {
        var _self = this;
        $('.checkitem').each(function () {
            if (!this.disabled) {
                $(this).prop('checked', _self.checked);
            }
        });
        $('.checkall').prop('checked', this.checked);
    });
    
    $(window).scroll(function () {
        var top = $(window).scrollTop();
        if (top >= 73) {
            $(".ncsc-layout-left ").addClass("sticky");
            $(".ncsc-layout-right ").addClass("sticky");
        } else {
            $(".ncsc-layout-left ").removeClass("sticky");
            $(".ncsc-layout-right ").removeClass("sticky");
        }
    });
    
    $('span.con a').click(function () {
        var e = $(this);
        var data_str = e.attr('data-param');
        eval("data_str = " + data_str);
        $.dialog.confirm('您确定要确认吗?', function () {
            $.post(SITE_URL + '?ctl=' + data_str.ctl + '&met=' + data_str.met + '&typ=json', {id: data_str.id}, function (data) {
                if (data && 200 == data.status) {
                    $.dialog.alert('确认成功', function () {
                        location.reload();
                    });
                } else {
                    $.dialog.alert('确认失败');
                }
            });
        });
    });
    $('label.del').click(function () {
        var e = $(this);
        var data_str = e.attr('data-param');
        eval("data_str = " + data_str);
        var length = $('.checkitem:checked').length;
        if (length > 0) {
            var chk_value = [];//定义一个数组
            $("input[name='chk[]']:checked").each(function () {
                chk_value.push($(this).val());//将选中的值添加到数组chk_value中
            });
            $.dialog.confirm('您确定要删除吗?', function () {
                $.post(SITE_URL + '?ctl=' + data_str.ctl + '&met=' + data_str.met + '&typ=json', {id: chk_value}, function (data) {
                    if (data && 200 == data.status) {
                        //$.dialog.alert('删除成功');
                        Public.tips.success('删除成功!');
                        window.location.reload(); //刷新当前页
                    } else {
                        //$.dialog.alert('删除失败');
                        Public.tips.error('删除失败!');
                    }
                });
            });
        }
        else {
            $.dialog.alert('请选择需要操作的记录');
        }
    });
});
