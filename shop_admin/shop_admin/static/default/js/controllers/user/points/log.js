//增加减少会员积分
$(function ()
{
	console.log($("#points-add-form").serialize());
    $('#points-add-form').validator({
        ignore: ':hidden',
        theme: 'yellow_bottom',
        timely: 1,
        stopOnError: true,
        fields: {
			'user_name': 'required;',
            'points_log_points': 'required;integer[+];',
            
        },
        valid: function (form)
        {	
			
            parent.$.dialog.confirm('确认积分操作？', function ()
                {
                    Public.ajaxPost(SITE_URL + '?ctl=User_Points&met=addPointsLog&typ=json', $("#points-add-form").serialize(), function (data)
                    {
                        console.log(data);
                        if (data.status == 200)
                        {
                            var msg= " "+ data.data.uname + ", 当前积分数为" + data.data.points;
                            $("#user_name").val(data.data.uname);
                            $("#user_id").val(data.data.points);
                            $("#td_memberinfo").text(msg);
                            parent.Public.tips({content: '操作成功！'});
                        }
                        else
                        {
                            parent.Public.tips({type: 1, content: data.msg || '操作无法成功，请稍后重试！'});
                        }
						
                       
                    });
				
			});
        },
    }).on("click", "a.submit-btn", function (e)
    {
        $(e.delegateTarget).trigger("validate");
    });

});

