/**
 * Created by yesai on 2016/5/17.
 */
//表单提交
$(function ()
{
    var t = "edit";
    if ($('#voucher_t_info').length > 0)
    {
        $('#voucher_t_info').validator({
           ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: true,
            fields: {
                'voucher_t_points':'required;integer[+0];'
            },
            valid: function (form)
            {
                parent.$.dialog.confirm('确认修改？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Promotion_Voucher&met=editVoucherTempInfo&typ=json', {voucher_t_id:$("#voucher_t_id").val(),voucher_t_points:$("input[name='voucher_t_points']").val(),voucher_t_state:$("input[name='voucher_t_state']:checked").val(),voucher_t_recommend:$("input[name='voucher_t_recommend']:checked").val()}, function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改成功！'});
                                callback && "function" == typeof callback && callback(data.data, t, window)
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
        }).on("click", "a#submitBtn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
    }
});

var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
