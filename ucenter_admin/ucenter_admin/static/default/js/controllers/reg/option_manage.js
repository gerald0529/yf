
var curRow, curCol, curArrears, $grid = $("#grid"), $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;

initPopBtns();
initField();

function initField()
{


    var datatypeCombo = Business.categoryCombo($('#reg_option_datatype_combo'), {
        editable: false,
        extraListHtml: '',
        /*
         addOptions: {
         value: -1,
         text: '选择应用'
         },
         */
        defaultSelected: 0,
        trigger: true,
        width: 120,
        callback: {
            onChange: function (data)
            {
                $('#reg_option_datatype').val(this.getValue());
            }
        }
    }, 'reg_option_datatype');

    var appCombo = Business.categoryCombo($('#option_id_combo'), {
        editable: false,
        extraListHtml: '',
        /*
         addOptions: {
         value: -1,
         text: '选择应用'
         },
         */
        defaultSelected: 0,
        trigger: true,
        width: 120,
        callback: {
            onChange: function (data)
            {
                $('#option_id').val(this.getValue());

                //更改状态
                switch (this.getValue())
                {
                    case 1:
                    case 2:
                    case 3:

                        datatypeCombo.selectByValue(0);
                        datatypeCombo.disable();


                        $('#reg_option_value').removeAttr("readonly");//去除input元素的readonly属性
                        $('#reg_option_value').removeClass('disabled')

                        break;
                    case 4:
                    case 5:
                    case 6:
                        datatypeCombo.enable();

                        $('#reg_option_value').attr("readonly","readonly")//将input元素设置为readonly
                        $('#reg_option_value').addClass('disabled')

                        break;
                }

            }
        }
    }, 'option_id');



    if (rowData.id)
    {
        $('#reg_option_id').val(rowData.reg_option_id);
        $('#reg_option_name').val(rowData.reg_option_name);
        $('#reg_option_order').val(rowData.reg_option_order);
        $('#option_id').val(rowData.option_id);
        $('#reg_option_required').val(rowData.reg_option_required);
        $('#reg_option_placeholder').val(rowData.reg_option_placeholder);
        $('#reg_option_datatype').val(rowData.reg_option_datatype);
        $('#reg_option_value').val(rowData.reg_option_value);






        appCombo.selectByValue(rowData.option_id);


        datatypeCombo.selectByValue(rowData.reg_option_datatype);



        if(rowData.reg_option_required)
        {
            $("#enable1").attr('checked', true);
            $("#enable0").attr('checked', false);
            $('[for="enable1"]').addClass('selected');
            $('[for="enable0"]').removeClass('selected');
        }
        else
        {
            $("#enable1").attr('checked', false);
            $("#enable0").attr('checked', true);
            $('[for="enable1"]').removeClass('selected');
            $('[for="enable0"]').addClass('selected');
        }

        if(rowData.reg_option_active)
        {
            $("#a-enable1").attr('checked', true);
            $("#a-enable0").attr('checked', false);
            $('[for="a-enable1"]').addClass('selected');
            $('[for="a-enable0"]').removeClass('selected');
        }
        else
        {
            $("#a-enable1").attr('checked', false);
            $("#a-enable0").attr('checked', true);
            $('[for="a-enable1"]').removeClass('selected');
            $('[for="a-enable0"]').addClass('selected');
        }

        //$('#keyword_find').attr("readonly", "readonly");
        //$('#keyword_find').addClass('ui-input-dis');
    }
}

function initPopBtns()
{
    var btn = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: btn[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.id);
            return cancleGridEdit(), $_form.trigger("validate"), !1;
        }
    }, {id: "cancel", name: btn[1]})
}

function postData(oper, id)
{
    $_form.validator({
        ignore: ':hidden',
        theme: 'yellow_bottom',
        timely: 1,
        stopOnError: true,
        fields: {
            //'keyword_find': 'required;'
        },
        valid: function (form)
        {
            var me = this;
            // 提交表单之前，hold住表单，防止重复提交
            me.holdSubmit();

            parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                {
                    /*
                     var keyword_find = $.trim($("#keyword_find").val());

                     var params = {keyword_find: keyword_find, keyword_replace: keyword_replace};
                     */
                    var n = "add" == oper ? _("新增") : _("修改");

                    Public.ajaxPost(SITE_URL + "?mdu=dev_shop&ctl=Reg_Option&typ=json&met=" + ("add" == oper ? "add" : "edit"), $_form.serialize(), function (resp)
                    {
                        if (200 == resp.status)
                        {
                            resp.data['id'] = resp.data['reg_option_id'];
                            parent.parent.Public.tips({content: n + "成功！"});
                            callback && "function" == typeof callback && callback(resp.data, oper, window)
                        }
                        else
                        {
                            parent.parent.Public.tips({type: 1, content: n + "失败！" + resp.msg})
                        }

                        // 提交表单成功后，释放hold，如果不释放hold，就变成了只能提交一次的表单
                        me.holdSubmit(false);
                    })
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

function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}

//设置表单元素回车事件
function bindEventForEnterKey()
{
    Public.bindEnterSkip($_form, function()
    {
        $('#grid tr.jqgrow:eq(0) td:eq(0)').trigger('click');
    });
}

function resetForm(t)
{
    $('#reg_option_id').val('');
    $('#reg_option_name').val('');
    $('#reg_option_order').val('');
    $('#option_id').val('');
    $('#reg_option_required').val('');
    $('#reg_option_placeholder').val('');
    $('#reg_option_datatype').val('');
    $('#reg_option_value').val('');

}

$(".box-main .form-section:has(label)").each(function(i, el)
{
    var $this = $(el),
        $label = $this.find('label'),
        $input = $this.find('.form-control');

    $input.on('focus', function()
    {
        $this.addClass('form-section-active');
        $this.addClass('form-section-focus');
    });

    $input.on('keydown', function()
    {
        $this.addClass('form-section-active');
        $this.addClass('form-section-focus');
    });

    $input.on('blur', function()
    {
        $this.removeClass('form-section-focus');

        if(!$.trim($input.val()))
        {
            $this.removeClass('form-section-active');
        }
    });

    $label.on('click', function()
    {
        $input.focus();
    });

    if($.trim($input.val()))
    {
        $this.addClass('form-section-active');
    }
});