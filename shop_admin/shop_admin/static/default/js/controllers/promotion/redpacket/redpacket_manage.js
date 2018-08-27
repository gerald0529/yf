/**
 * Created by yesai on 2016/5/17.
 */
//表单提交
$(function ()
{
    var t = "edit";
    if ($('#manage-form').length > 0)
    {
        $('#manage-form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: true,
            fields: {
                'redpacket_t_desc':'redpacket_t_desc;length[~200];'
            },
            valid: function (form)
            {
                parent.$.dialog.confirm('确认修改？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Promotion_RedPacket&met=editRedPacketTempInfo&typ=json', {redpacket_t_id:$("#redpacket_t_id").val(),redpacket_t_state:$("input[name='redpacket_t_state']:checked").val(),redpacket_t_recommend:$("input[name='redpacket_t_recommend']:checked").val(),redpacket_t_desc:$("#redpacket_t_desc").val()}, function (data)
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
