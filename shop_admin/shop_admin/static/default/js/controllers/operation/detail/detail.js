//平台审核
$(function ()
{
    urlParam = Public.urlParam();
    
    $('#handle_confirm').validator({
        ignore: ':hidden',
        theme: 'yellow_bottom',
        timely: 1,
        stopOnError: true,
        fields: {
        },
        valid: function (form)
        {
            var me = this;
            // 提交表单之前，hold住表单，防止重复提交
            me.holdSubmit();

            parent.$.dialog.confirm('确认审核此结算单？', function ()
                {
                    Public.ajaxPost(SITE_URL + '?ctl=Operation_Settlement&met=updateStatu&typ=json', $("#handle_confirm").serialize(), function (data)
                    {
                        if (data.status == 200)
                        {
                            parent.Public.tips({content: '审核成功！'});
                            window.location.href = SITE_URL+"?ctl=Operation_Settlement&met=detail&id="+urlParam.id;
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
    }).on("click", "a#btn_handle_submit", function (e)
    {
        $(e.delegateTarget).trigger("validate");
    });

});


//确认付款
$(function ()
{
    $('#handle_finish').validator({
        ignore: ':hidden',
        theme: 'yellow_bottom',
        timely: 1,
        stopOnError: true,
        fields: {
            'os_pay_content': 'required;'
        },
        valid: function (form)
        {
            parent.$.dialog.confirm('确认此结算单已完成付款？', function ()
                {
                    Public.ajaxPost(SITE_URL + '?ctl=Operation_Settlement&met=updateStatu&typ=json', $("#handle_finish").serialize(), function (data)
                    {
                        if (data.status == 200)
                        {
                            parent.Public.tips({content: '操作成功！'});
                            window.location.href = SITE_URL+"?ctl=Operation_Settlement&met=detail&id="+urlParam.id;
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
        }
    }).on("click", "a#btn_handle_submit", function (e)
    {
        $(e.delegateTarget).trigger("validate");
    });
});