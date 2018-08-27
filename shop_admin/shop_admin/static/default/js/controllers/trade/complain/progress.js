//审核
$(function ()
{
    $('#verify_form').validator({
        ignore: ':hidden',
        theme: 'yellow_bottom',
        timely: 1,
        stopOnError: true,
        fields: {
            //'icp_number': 'required;email;'
        },
        valid: function (form)
        {
            var me = this;
            // 提交表单之前，hold住表单，防止重复提交
            me.holdSubmit();

            parent.$.dialog.confirm('确认审核此投诉？', function ()
                {
                    Public.ajaxPost(SITE_URL + '?ctl=Trade_Complain&met=verifyComplain&typ=json', $("#verify_form").serialize(), function (data)
                    {
                        if (data.status == 200)
                        {
                            parent.Public.tips({content: '审核成功！'});
                            window.location.href = SITE_URL + "?ctl=Trade_Complain&met=complain&state=2";
                        }
                        else
                        {
                            parent.Public.tips({type: 1, content: data.msg || '操作无法成功，请稍后重试！'});
                        }

                        // 提交表单成功后，释放hold，如果不释放hold，就变成了只能提交一次的表单
                        me.holdSubmit(false);
                    });
                },
                function ()
                {
                    me.holdSubmit(false);
                });
        },
    }).on("click", "a#verify_button", function (e)
    {
        $(e.delegateTarget).trigger("validate");
    });

});


//关闭投诉
$(function ()
{
    $("#close_button").click(function ()
    {
        $("final_handle_message").text('');
        $(".complain_dialog").show();
        $("#close_complain").hide();
    });

    $("#btn_close_cancel").click(function ()
    {
        $(".complain_dialog").hide();
        $("#close_complain").show();
    });

    var final_handle_message = $("#final_handle_message").val();
    $('#close_form').validator({
        ignore: ':hidden',
        theme: 'yellow_bottom',
        timely: 1,
        stopOnError: true,
        fields: {
            'final_handle_message': 'required;'
        },
        valid: function (form)
        {
            parent.$.dialog.confirm('确认关闭此投诉？', function ()
                {
                    Public.ajaxPost(SITE_URL + '?ctl=Trade_Complain&met=handleComplain&typ=json', $("#close_form").serialize(), function (data)
                    {
                        if (data.status == 200)
                        {
                            parent.Public.tips({content: '操作成功！'});
                            window.location.href = SITE_URL + "?ctl=Trade_Complain&met=complain&state=5";
                        }
                        else
                        {
                            parent.Public.tips({type: 1, content: data.msg || '操作无法成功，请稍后重试！'});
                        }
                    });
                },
                function ()
                {
                });
        },
    }).on("click", "a#btn_handle_submit", function (e)
    {
        $(e.delegateTarget).trigger("validate");
    });
});

//返回
$(function ()
{
    $("#new_return_button").click(function ()
    {
        window.location.href = SITE_URL + "?ctl=Trade_Complain&met=complain&state=1";
    });

    $("#appeal_return_button").click(function ()
    {
        window.location.href = SITE_URL + "?ctl=Trade_Complain&met=complain&state=2";
    });

    $("#talk_return_button").click(function ()
    {
        window.location.href = SITE_URL + "?ctl=Trade_Complain&met=complain&state=3";
    });

    $("#handle_return_button").click(function ()
    {
        window.location.href = SITE_URL + "?ctl=Trade_Complain&met=complain&state=4";
    });

    $("#finish_return_button").click(function ()
    {
        window.location.href = SITE_URL + "?ctl=Trade_Complain&met=complain&state=5";
    });
});


$(function ()
{
    var complain_id = $('#complain_id').val();

    get_complain_talk();
    $("#btn_publish").click(function ()
    {
        if ($("#complain_talk").val() == '')
        {
            parent.Public.tips({type:1,content: '对话不能为空！'});
            //alert("对话不能为空");
        }
        else
        {
            publish_complain_talk();
        }
    });
    $("#btn_refresh").click(function ()
    {
        get_complain_talk();
    });

    //发布对话
    function publish_complain_talk()
    {
        $.ajax({
            type: 'POST',
            url: SITE_URL + '?ctl=Trade_Complain&met=publishComplainTalk&typ=json',
            cache: false,
            data: "complain_id=" + complain_id + "&complain_talk=" + encodeURIComponent($("#complain_talk").val()) + "&user_id=" + $("#user_id_final_handle").val() + "&user_account=" + $("#user_account_final_handle").val() + "&member_type=3",
            dataType: 'json',
            error: function ()
            {
                parent.Public.tips({type:1,content: '对话发送失败！'});
                //alert("对话发送失败");
            },
            success: function (d)
            {
                if (d.msg == 'success')
                {
                    $("#complain_talk").val('');
                    //get_complain_talk();
                    parent.Public.tips({content: '对话发送成功！'});
                    //alert("对话发送成功");
                    get_complain_talk();
                }
                else
                {
                    parent.Public.tips({type:1,content: '对话发送失败！'});
                    //alert("对话发送失败");
                }
            }
        });
    }


});