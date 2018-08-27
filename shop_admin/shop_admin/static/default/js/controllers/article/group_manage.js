function initField()
{
    if(api.data.parent_id)
    {
        $.get(SITE_URL + '?ctl=Article_Group&met=getGroupName&typ=json&id=' + api.data.parent_id, function(a){
            if(a.status==200)
            {
                $("#parent_name").val(a.data.parent_name);
                $("#parent_id").val(a.data.parent_id);
            }
        });
    }
    if (rowData.article_group_id)
    {
        $("#article_group_sort").val(rowData.article_group_sort);
        $("#article_group_title").val(rowData.article_group_title);
        if(rowData.article_group_parent_id)
        {
            $.get(SITE_URL + '?ctl=Article_Group&met=getGroupName&typ=json&id=' + rowData.article_group_parent_id, function(a){
                if(a.status==200)
                {
                    $("#parent_name").val(a.data.parent_name);
                    $("#parent_id").val(a.data.parent_id);
                }
            });
        }
    }
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.article_group_id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
    var article_group_sort = $.trim($("#article_group_sort").val()),
        article_group_title = $.trim($("#article_group_title").val()),
        article_group_parent_id = $.trim($("#parent_id").val()),
        n = "add" == t ? "新增类型" : "修改类型";

    params = rowData.article_group_id ? {
        article_group_id: e,
        article_group_sort: article_group_sort,
        article_group_title: article_group_title,
        article_group_parent_id: article_group_parent_id
    } : {
        article_group_sort: article_group_sort,
        article_group_title: article_group_title,
        article_group_parent_id: article_group_parent_id
    };
    Public.ajaxPost(SITE_URL + "?ctl=Article_Group&typ=json&met=" + ("add" == t ? "addGroup" : "editGroup"), params, function (e)
    {
        if (200 == e.status)
        {
            parent.parent.Public.tips({content: n + "成功！"});
            callback && "function" == typeof callback && callback(e.data, t, window)
        }
        else
        {
            parent.parent.Public.tips({type: 1, content: n + "失败！" + e.msg})
        }
    })
}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}
function resetForm(t)
{
    $("#manage-form").validate().resetForm();
    $("#article_group_sort").val("");
    $("#article_group_title").val("");
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();