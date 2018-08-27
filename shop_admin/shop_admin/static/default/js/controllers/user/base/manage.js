function initField()
{
    if (rowData.user_id)
    {
        $("#user_account").val(rowData.user_account);
        $("#user_realname").val(rowData.user_realname);
        $("#rights_group_id").val(rowData.rights_group_id)
        $("#subsite_id").val(rowData.sub_site_id)
        
    }
}
function initEvent()
{
    var t = $("#number");
    Public.limitInput(t, /^[a-zA-Z0-9\-_]*$/);
    Public.bindEnterSkip($("#manage-wrap"), postData, oper, rowData.user_id);
    initValidator();
    t.focus().select();

    $("#rights_group_id").data("defItem",["rights_group_id",rowData.rights_group_id]);
    rights_group_id = $("#rights_group_id").combo({
        data: "./index.php?ctl=Rights_Group&met=rightsGroupList&typ=json",
        ajaxOptions: {
            formatData: function (e)
            {
                return e.data.items;
            }
        },
        value: "rights_group_id",
        text:  "rights_group_name",
        width: 210,
        defaultSelected: rowData.rights_group_id ? $("#rights_group_id").data("defItem") : void 0,
        editable: true,
        maxListWidth: 500,
    }).getCombo();
    
    $("#subsite_id").data("defItem",["subsite_id",rowData.sub_site_id]);
    subsite_id = $("#subsite_id").combo({
        data: "./index.php?ctl=Subsite_Config&met=getSubsiteListDefault&typ=json",
        ajaxOptions: {
            formatData: function (e)
            {
                return e.data.items;
            }
        },
        value: "subsite_id",
        text:  "sub_site_name",
        width: 210,
        defaultSelected: rowData.sub_site_id ? $("#subsite_id").data("defItem") : void 0,
        editable: true,
        maxListWidth: 500,
    }).getCombo();
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.user_id);
            return !1
        }
    }, {id: "cancel", name: t[1]})
    oper == 'edit' && $('#user_account').attr('disabled','disabled');
}
function initValidator()
{
    $.validator.addMethod("number", function (t)
    {
        return /^[a-zA-Z0-9\-_]*$/.test(t)
    });
    $("#manage-form").validate({
        rules: {number: {required: !0, number: !0}, name: {required: !0}},
        messages: {number: {required: "职员编号不能为空", number: "职员编号只能由数字、字母、-或_等字符组成"}, name: {required: "职员名称不能为空"}},
        errorClass: "valid-error"
    })
}
function postData(t, e)
{
    if ($("#manage-form").validate().form())
    {
        var i = $.trim($("#user_account").val()), a = $.trim($("#user_password").val()), r = $.trim($("#user_realname").val()), s = rights_group_id.getValue(), n = "add" == t ? "新增用户" : "修改用户", site = subsite_id.getValue();
        params = rowData.user_id ? {user_id: e, user_account: i, user_password: a, user_realname: r, rights_group_id: s, subsite_id: site} : {user_account: i, user_password: a, user_realname: r, rights_group_id: s, subsite_id: site};
        Public.ajaxPost("./index.php?ctl=User_Base&typ=json&met=" + ("add" == t ? "add" : "edit"), params, function (e)
        {
            if (200 == e.status)
            {
                parent.parent.Public.tips({content: n + "成功！"});
                $('#user_account, #user_password, #user_realname').val('');
                callback && "function" == typeof callback && callback(e.data, t, window)
            }
            else
            {
                parent.parent.Public.tips({type: 1, content: n + "失败！" + e.msg})
            }
        })
    }
    else
    {
        $("#manage-form").find("input.valid-error").eq(0).focus()
    }
}
function resetForm(t)
{
    $("#manage-form").validate().resetForm();
    $("#name").val("");
    $("#number").val(Public.getSuggestNum(t.employee_number)).focus().select()
}
var api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();
initEvent();
