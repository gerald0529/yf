function initField()
{
    if(api.data.parent_id)
    {
        $.get(SITE_URL + '?ctl=Base_District&met=getDistrictName&typ=json&id=' + api.data.parent_id, function(a){
            if(a.status==200)
            {
                $("#parent_district").val(a.data.district_name);
                $("#parent_id").val(a.data.id);
            }
        });
    }
    if (rowData.district_id)
    {
        $("#district_name").val(rowData.district_name);
        if(rowData.district_parent_id)
        {
            $.get(SITE_URL + '?ctl=Base_District&met=getDistrictName&typ=json&id=' + rowData.district_parent_id, function(a){
                if(a.status==200)
                {
                    $("#parent_district").val(a.data.district_name);
                    $("#parent_id").val(a.data.id);
                }
            });
        }
        //$("#parent_district").val(rowData.parent_district);
        $("#district_region").val(rowData.district_region);
    }
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.district_id);
            return cancleGridEdit(), $("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
    var district_name = $.trim($("#district_name").val()),
        district_region = $.trim($("#district_region").val()),
        parent_district = $.trim($("#parent_id").val()),

        n = "add" == t ? "新增类型" : "修改类型";

    var params = {district_name: district_name, district_region: district_region,parent_district:parent_district};
    e ? params.district_id= e : '';
    Public.ajaxPost(SITE_URL +"?ctl=Base_District&typ=json&met=" + ("add" == t ? "addDistrict" : "editDistrict"), params, function (e)
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

var curRow, curCol, curArrears, $grid = $("#grid"), $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
console.info(rowData);
initPopBtns();
initField();