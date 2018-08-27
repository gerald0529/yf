//平台审核
$(function () {
    urlParam = Public.urlParam();
    
    $('#handle_finish').validator({
        ignore: ':hidden',
        theme: 'yellow_bottom',
        timely: 1,
        stopOnError: true,
        fields: {
            return_platform_message: "required"
        },
        valid: function (form) {
            var me = this;
            // 提交表单之前，hold住表单，防止重复提交
            me.holdSubmit();
            parent.$.dialog.confirm('确认审核此退单？', function () {
                    $("#btn_handle_submit").attr("disabled", "true");
                    Public.ajaxPost(SITE_URL + '?ctl=Trade_Return&met=agree&typ=json', $("#handle_finish").serialize(), function (data) {
                        // alert(SITE_URL);
                        if (data.status == 200) {
                            parent.Public.tips({content: '审核成功！'});
                            window.location.href = SITE_URL + "?ctl=Trade_Return&met=detail&id=" + urlParam.id;
                        } else {
                            // alert(4444444444);
                            parent.Public.tips({type: 1, content: data.msg || '操作无法成功，请稍后重试！'});
                        }
                        // 提交表单成功后，释放hold，如果不释放hold，就变成了只能提交一次的表单
                        me.holdSubmit(false);
                        $('#btn_handle_submit').removeAttr('disabled');
                    });
                },
                function () {
                    me.holdSubmit(false);
                });
        },
    }).on("click", "a#btn_handle_submit", function (e) {
        if ($("#btn_handle_submit").attr("disabled") != 'disabled') {
            $(e.delegateTarget).trigger("validate");
        }
        
    });
    
    
    $("#btn_handle_unpass").on("click", function (a) {
        // 提交表单之前，hold住表单，防止重复提交
        parent.$.dialog.confirm('确认不同意此退单？', function () {
            Public.ajaxPost(SITE_URL + '?ctl=Trade_Return&met=refuse&typ=json', $("#handle_finish").serialize(), function (data) {
                if (data.status == 200) {
                    parent.Public.tips({content: '操作成功！'});
                    window.location.href = SITE_URL + "?ctl=Trade_Return&met=detail&id=" + urlParam.id;
                } else {
                    parent.Public.tips({type: 1, content: data.msg || '操作无法成功，请稍后重试！'});
                }
            });
        });
    });
    
    
});