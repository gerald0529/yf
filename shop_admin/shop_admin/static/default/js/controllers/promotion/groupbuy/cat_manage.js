$(".cat_type").click(function () {
    var value = $("#manage-form input[name=\"cat_type\"]:checked ").val();
    $("#groupbuy_cat_type").val(value);
});


function initField() {
    if (api.data.parent_id) //添加子分类
    {
        $.get(SITE_URL + "?ctl=Promotion_GroupBuy&met=getGroupBuyCatName&typ=json&id=" + api.data.parent_id, function (a) {
            if (a.status == 200) {
                $("#parent_cat").val(a.data.groupbuy_cat_name);
                $("#parent_id").val(a.data.id);
                $("#groupbuy_cat_type").val(a.data.groupbuy_cat_type);
                $("input[name='cat_type'][value='" + a.data.groupbuy_cat_type + "']").attr("checked", true);
                $("input:radio").attr("disabled", "disabled");
                
            }
        });
    }
    //编辑分类
    if (rowData.groupbuy_cat_id) {
        $("#groupbuy_cat_name").val(rowData.groupbuy_cat_name);
        $("#groupbuy_cat_type").val(rowData.groupbuy_cat_type);
        $("#groupbuy_cat_sort").val(rowData.groupbuy_cat_sort);
        $("input[name='cat_type'][value='" + rowData.groupbuy_cat_type + "']").attr("checked", true);
        $("input:radio").attr("disabled", "disabled");
        //如果有上级
        if (rowData.groupbuy_cat_parent_id) {
            $.get(SITE_URL + "?ctl=Promotion_GroupBuy&met=getGroupBuyCatName&typ=json&id=" + rowData.groupbuy_cat_parent_id, function (a) {
                if (a.status == 200) {
                    $("#parent_cat").val(a.data.groupbuy_cat_name);
                    $("#parent_id").val(a.data.id);
                    $("#groupbuy_cat_type").val(a.data.groupbuy_cat_type);
                    $("input[name='cat_type'][value='" + a.data.groupbuy_cat_type + "']").attr("checked", true);
                    $("input:radio").attr("disabled", "disabled");
                }
            });
        }
    }
}

function initPopBtns() {
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function () {
            postData(oper, rowData.groupbuy_cat_id);
            return cancleGridEdit(), $("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]});
}

function postData(t, e) {
    var groupbuy_cat_name = $.trim($("#groupbuy_cat_name").val()),
        
        parent_cat = $.trim($("#parent_id").val()),
        
        groupbuy_cat_type = $.trim($("#groupbuy_cat_type").val());
    
    groupbuy_cat_sort = $.trim($("#groupbuy_cat_sort").val());
    
    n = "add" == t ? "新增分类" : "修改分类";
    
    var params = {groupbuy_cat_name: groupbuy_cat_name, parent_cat: parent_cat, groupbuy_cat_type: groupbuy_cat_type, groupbuy_cat_sort: groupbuy_cat_sort};
    e ? params.groupbuy_cat_id = e : "";
    Public.ajaxPost(SITE_URL + "?ctl=Promotion_GroupBuy&typ=json&met=" + ("add" == t ? "addGroupBuyCat" : "editGroupBuyCat"), params, function (e) {
        if (200 == e.status) {
            parent.Public.tips({content: n + "成功！"});
            callback && "function" == typeof callback && callback(e.data, t, window);
        }
        else {
            parent.Public.tips({type: 1, content: n + "失败！" + e.msg});
        }
    });
}

function cancleGridEdit() {
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null);
}

var curRow, curCol, curArrears, $grid = $("#grid"), $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
console.info(rowData);
initPopBtns();
initField();
