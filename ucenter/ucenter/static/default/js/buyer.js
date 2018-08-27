$(function(){
	$(".ncbtn").hover(function(){
		$(this).find(".more").show();
	},function(){
		$(this).find(".more").hide();
	})
	var onOff=true;
	$(".click_btn").click(function(){
		var click_show=$(this).parent().find(".click_show");
		if(onOff){
			click_show.show();
			onOff=false;
		}else{
			click_show.hide();
			onOff=true;
		}
		
	})
	$(".tracks_con_more ul li").hover(function(){
		$(this).addClass("li_hover");
		$(this).find("a").last().addClass("lia_hover");
		$(this).find("i").css("color","#fff");
	},function(){
		$(this).removeClass("li_hover");
		$(this).find("a").last().removeClass("lia_hover");
		$(this).find("i").css("color","red");
	})
	
	
	
	 //============删除操作========
   	$('label.del a').click(function(){
		var e = $(this);
		var data_str = e.attr('data-param');
		eval( "data_str = "+data_str);
		var length = $('.checkitem:checked').length;
		if(length > 0){
			var chk_value =[];//定义一个数组
			$("input[name='chk[]']:checked").each(function(){
				chk_value.push($(this).val());//将选中的值添加到数组chk_value中
			});
			$.dialog.confirm('您确定要删除吗?',function(){
				$.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{id:chk_value},function(data)
				{
					if(data && 200 == data.status) {
						//$.dialog.alert('删除成功',function(){location.reload();});
						Public.tips({ content: "删除成功！"});
						location.reload();
					} else {
						//$.dialog.alert('删除失败');
						Public.tips({type: 1, content: "删除失败！"});
					}
				});
			});
		}
		else
		{
			$.dialog.alert('请选择需要操作的记录');
		}
	});
	
	 $('span.del a').click(function(){

		 if($(this).attr("data-dis")){
			 return 0;
		 }
		var e = $(this);
		var data_str = e.attr('data-param');
		eval( "data_str = "+data_str);
		$.dialog.confirm('您确定要删除吗?',function(){
			$.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{id:data_str.id},function(data)
			{
				//alert(JSON.stringify(data));
				if(data && 200 == data.status) {
					e.parents('tr').hide('slow', function() {
						var row_count = $('#table_list').find('.row_line:visible').length;
						if(row_count <= 0)
						{
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

	$('span.cancel a').click(function(){

		if($(this).attr("data-dis")){
			return 0;
		}
		var e = $(this);
		var data_str = e.attr('data-param');
		eval( "data_str = "+data_str);
		$.dialog.confirm('您确定要取消吗?',function(){
			$.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{id:data_str.id},function(data)
			{
				//alert(JSON.stringify(data));
				if(data && 200 == data.status) {
					location.reload();
				} else {
					// showError(data.message);
					//$.dialog.alert('删除失败!');
					Public.tips({type: 1, content: "取消失败！"});
				}
			});
		});
	});

	$('.checkall').click(function(){
		var _self = this;
		$('.checkitem').each(function(){
			if (!this.disabled){
				$(this).prop('checked', _self.checked);
			}
		});
		$('.checkall').prop('checked', this.checked);
	});
	$('label.del').click(function(){
        var e = $(this);
        var data_str = e.attr('data-param');
        eval( "data_str = "+data_str);
        var length = $('.checkitem:checked').length;
        if(length > 0){
            var chk_value =[];//定义一个数组
            $("input[name='chk[]']:checked").each(function(){
                chk_value.push($(this).val());//将选中的值添加到数组chk_value中
            });
            $.dialog.confirm('您确定要删除吗?',function(){
                $.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{id:chk_value},function(data)
                {
                    if(data && 200 == data.status) {
                        //$.dialog.alert('删除成功',function(){location.reload();});
                        Public.tips({ content: "删除成功！"});
                        location.reload();
                    } else {
                        //$.dialog.alert('删除失败');
                        Public.tips({type: 1, content: "删除失败！"});
                    }
                });
            });
        }
        else
        {
            $.dialog.alert('请选择需要操作的记录');
        }
    });
})