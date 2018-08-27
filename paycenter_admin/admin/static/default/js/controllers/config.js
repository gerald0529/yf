$(function ()
{

    if ($('#shop-setting-form').length > 0)
    {
        var catorageCombo = Business.timeZoneCombo($('#time_zone'), {
            editable: false,
            extraListHtml: '',
            //addOptions: {value: -1, text: '选择类别'},
            defaultSelected: null,
            trigger: true,
            width: 120 * 3,
            callback: {
                onChange: function (data)
                {
                    //alert(this.getText());
                    $('#time_zone_id').val(this.getValue());
                }
            }
        }, 'customertype');

        catorageCombo.selectByValue(time_zone_id);


        var languageCombo = Business.languageCombo($('#language'), {
            editable: false,
            extraListHtml: '',
            //addOptions: {value: -1, text: '选择类别'},
            defaultSelected: null,
            trigger: true,
            width: 120 * 3,
            callback: {
                onChange: function (data)
                {
                    //alert(this.getText());
                    $('#language_id').val(this.getValue());
                }
            }
        });

        languageCombo.selectByValue(language_id);



        var dateFormatCombo = Business.categoryCombo($('#date_format_combo'), {
            editable: false,
            extraListHtml: '',
            //addOptions: {value: -1, text: '选择类别'},
            defaultSelected: null,
            trigger: true,
            width: 120 * 3,
            callback: {
                onChange: function (data)
                {
                    //alert(this.getText());
                    $('#date_format').val(this.getValue());
                }
            }
        }, 'date_format');

        dateFormatCombo.selectByValue(date_format_combo);

        var timeFormatCombo = Business.categoryCombo($('#time_format_combo'), {
            editable: false,
            extraListHtml: '',
            //addOptions: {value: -1, text: '选择类别'},
            defaultSelected: null,
            trigger: true,
            width: 120 * 3,
            callback: {
                onChange: function (data)
                {
                    //alert(this.getText());
                    $('#time_format').val(this.getValue());
                }
            }
        }, 'time_format');

        timeFormatCombo.selectByValue(time_format_combo);


        $('#shop-setting-form').validator({
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

                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=edit&typ=json', $('#shop-setting-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
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
        }).on("click", "a.submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
    }



    if ($('#theme-setting-form').length > 0)
    {
        var themeCombo = Business.themeCombo($('#theme'), {
            editable: false,
            extraListHtml: '',
            //addOptions: {value: -1, text: '选择类别'},
            defaultSelected: null,
            trigger: true,
            width: 120 * 3,
            callback: {
                onChange: function (data)
                {
                    //alert(this.getText());
                    $('#theme_id').val(this.getValue());
                }
            }
        });

        themeCombo.selectByValue(theme_id);

        $('#theme-setting-form').validator({
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

                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=edit&typ=json', $('#theme-setting-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
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
        }).on("click", "a.submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
    }

});






$(function ()
{
    if ($('#shop_api-setting-form').length > 0)
    {

        $('#shop_api-setting-form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
            fields: {
                'shop_api[shop_api_url]': 'required;',
                'shop_api[shop_api_key]': 'required;'
            },
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=editShopApi&typ=json', $('#shop_api-setting-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
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
        }).on("click", "a.submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });


        $('#ucenter-shop_api-setting-form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
            fields: {
                'ucenter_api[ucenter_api_url]': 'required;',
                'ucenter_api[ucenter_api_key]': 'required;'
            },
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=editUcenterApi&typ=json', $('#ucenter-shop_api-setting-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
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
        }).on("click", "a.ucenter-submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });



        $('#paycenter-shop_api-setting-form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
            fields: {
                'paycenter_api[paycenter_api_url]': 'required;',
                'paycenter_api[paycenter_api_key]': 'required;'
            },
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=editPaycenterApi&typ=json', $('#paycenter-shop_api-setting-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
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
        }).on("click", "a.paycenter-submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
    }
});

//email  msg
$(function ()
{
    if ($('#email_msg-setting-form').length > 0)
    {
        $('#email_msg-setting-form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
            fields: {
                'email[email_host]': 'required;',
                'email[email_addr]': 'required;',
                'email[email_pass]': 'required;'
            },
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=edit&typ=json', $('#email_msg-setting-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
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
        }).on("click", "a.submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });


        $('#send_test_email').click(function ()
        {
            $.ajax({
                type: 'POST',
                url: SITE_URL + '?ctl=Config&met=testEmail&typ=json',
                data: $('#email_msg-setting-form').serialize(),
                error: function ()
                {
                    alert('测试邮件发送失败，请重新配置邮件服务器');
                },
                success: function (html)
                {
                    alert(html.msg);
                },
                dataType: 'json'
            });
        });

    }



    if ($('#sms-setting-form').length > 0)
    {
        $('#sms-setting-form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
            fields: {
                'sms[sms_account]': 'required;',
                'sms[sms_pass]': 'required;'
            },
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=edit&typ=json', $('#sms-setting-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
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
        }).on("click", "a.submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });


        $('#send_test_sms').click(function ()
        {
            $.ajax({
                type: 'POST',
                url: SITE_URL + '?ctl=Config&met=testSms&typ=json',
                data: $('#sms-setting-form').serialize(),
                error: function ()
                {
                    alert('发送失败，请重新配置短信账号');
                },
                success: function (html)
                {
                    alert(html.msg);
                },
                dataType: 'json'
            });
        });

    }
});

//消息模板


//设置
$(function ()
{
    if ($('#site-config-form').length > 0)
    {

        $('#site-config-form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
            fields: {
               'setting[setting_email]': 'email;'

            },
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {

                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=edit&typ=json', $('#site-config-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
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
        }).on("click", "a.submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
    }

});



//seo设置 首页
$(function ()
{
    if ($('#seo-setting-form').length > 0)
    {
        $('#seo-setting-form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
            fields: {

            },
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=edit&typ=json', $('#seo-setting-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
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
        }).on("click", "a.submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
    }

});





//upload
$(function ()
{
    if ($('#upload-setting-form').length > 0)
    {
        $('#upload-setting-form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
            fields: {
                'upload[image_max_filesize]': 'required;integer[+];range[0~' + max_upload_file_size + ']',
                'upload[image_allow_ext]': 'required;'
            },
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=edit&typ=json', $('#upload-setting-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
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
        }).on("click", "a.submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
    }
});

//shop api


$(function ()
{
    if ($('#shop_api-setting-form').length > 0)
    {

        $('#shop_api-setting-form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
            fields: {
                'shop_api[shop_api_url]': 'required;',
                'shop_api[shop_api_key]': 'required;'
            },
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=editShopApi&typ=json', $('#shop_api-setting-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
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
        }).on("click", "a.submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });

    }

    if ($('#ucenter-shop_api-setting-form').length)
    {


        $('#ucenter-shop_api-setting-form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
            fields: {
                'ucenter_api[ucenter_api_url]': 'required;',
                'ucenter_api[ucenter_api_key]': 'required;'
            },
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=editUcenterApi&typ=json', $('#ucenter-shop_api-setting-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
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
        }).on("click", "a.ucenter-submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });


    }

    if ($('#paycenter-shop_api-setting-form').length)
    {

        $('#paycenter-shop_api-setting-form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
            fields: {
                'paycenter_api[paycenter_api_url]': 'required;',
                'paycenter_api[paycenter_api_key]': 'required;'
            },
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=editPaycenterApi&typ=json', $('#paycenter-shop_api-setting-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
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
        }).on("click", "a.paycenter-submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
    }

});


