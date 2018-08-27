var Public = Public || {},
 Business = Business || {},
    $_form = $("#manage-form"),
 defaultPage = Public.getDefaultPage();

var property_format_row = [{"id":"select","name":"select"}];//,{"id":"checkbox","name":"checkbox"},{"id":"text","name":"text"}

var propertyCombo = Business.propertyFormatCombo($('#property_format'), {
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
            //$('#property_format').val(this.getValue());
        }
    }
});


var handle = {del: function (t)
    {
        $.dialog.confirm("请确认是否删除？", function ()
        {
            parent.Public.tips({content: "属性删除成功！"});
            $("#grid").jqGrid("delRowData", t)
        })
    }
};

function initField()
{

    if (rowData.id)
    {
        propertyCombo.selectByValue(rowData.property_format);
        propertyCombo.disable();


        $("#property_name").val(rowData.property_name);
        $("#property_displayorder").val(rowData.property_displayorder);
        if(rowData.property_is_search==1)
        {
            $('#property_is_search1').attr('checked', true);
            $('#property_is_search0').attr('checked', false);
            $('[for="property_is_search1"]').addClass('selected');
            $('[for="property_is_search0"]').removeClass('selected');
        }
        else
        {
            $('#property_is_search1').attr('checked', false);
            $('#property_is_search0').attr('checked', true);
            $('[for="property_is_search1"]').removeClass('selected');
            $('[for="property_is_search0"]').addClass('selected');
        }
        getData();
    }
    else
    {
        var data = [{id:0}];
        initGrid(data);
    }
}

function getData()
{
    var p_id = rowData.id;
    $.get('./index.php?ctl=Goods_Property&met=getPropertyValue&typ=json&id='+p_id,function(b)
        {
            if(b.status==200)
            {
                var data = b.data;
                var thisGridData = data && data.length ? data : (data = [{id:0}])
                initGrid(thisGridData);
            }
        }
    );
}

function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
    var property_name = $.trim($("#property_name").val()),
        property_displayorder = $.trim($("#property_displayorder").val()),
        property_is_search = $("input[name='property_is_search']:checked").val();

        n = "add" == t ? "新增属性" : "修改属性";
    $('#grid').jqGrid('saveCell', curRow, curCol);
    var por = $('#grid').jqGrid('getRowData');
    var property_rows = {};
    $.each(por,function(name,value){
        var array = {};
        array['property_value_displayorder'] = value.property_value_displayorder;
        array['property_value_name'] = value.property_value_name;
        array['property_value_id']  = $(value.operate).data('is_update') ? $(value.operate).data('id') : $(value.operate).data('is_update');
        property_rows[name] = array;
    })


    params = rowData.property_id ? {
        property_id: e,
        type_id: type_id,
        property_name: property_name,
        property_displayorder: property_displayorder,
        property_is_search: property_is_search,
        property_format: propertyCombo.getValue(),
        property_rows :property_rows
    } : {
        type_id: type_id,
        property_name: property_name,
        property_displayorder: property_displayorder,
        property_is_search: property_is_search,
        property_format: propertyCombo.getValue(),
        property_rows :property_rows
    };
    Public.ajaxPost("./index.php?ctl=Goods_Property&typ=json&met=" + ("add" == t ? "addProperty" : "editProperty"), params, function (e)
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
//Business.billsEvent(this, 'property');
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}
function resetForm(t)
{
    $("#manage-form").validate().resetForm();
    $("#vendor_type_name").val("");
    $("#vendor_type_desc").val("");
}

Public.propertyOper = function (val, opt, row) {
    var html_con = '<div class="operating" data-is_update="' + (row.property_value_id ? row.property_value_id : 0) + '" data-id="' + opt.rowId + '"><span class="ui-icon ui-icon-plus" title="新增行">&#xe605;</span><span class="ui-icon ui-icon-trash" title="删除行"></span></div>';
    return html_con;
};

function initGrid(data)
{
    var t = ["操作", "排序", "可选值"], e = [{
        name: "operate",
        width: 100,
        fixed: !0,
        align: "center",
        formatter: Public.propertyOper
    },
        {name: "property_value_displayorder", index: "property_value_displayorder", align: "center",width: 100, editable: !0},
        {name: "property_value_name", index: "property_value_name", width: 310, editable: !0},
    ];

    $("#grid").jqGrid({
        cellEdit: true,
        data: data,
        datatype: "local",
        height: 200,
        altRows: !0,
        gridview: !0,
        colNames: t,
        colModel: e,
        cellsubmit: "clientArray",
        cmTemplate: {
            sortable: !1,
            title: !1
        },
        localReader: {
            id: "id"
        },
        shrinkToFit: !1,
        loadComplete: function ()
        {
            var e = {};
            t = data;
            for (var i = 0; i < t.length; i++)
            {
                var a = t[i];
                e[a.id] = a;
            }

            $("#grid").data("gridData", e);
        }
    });
}

THISPAGE = {
    newId: 1
}

function initEvent()
{
    var _self = THISPAGE;

    //新增分录
    $('.grid-wrap').on('click', '.ui-icon-plus', function(e){
        var rowId = $(this).parent().data('id');
        var newId = $('#grid tbody tr').length;
        var datarow = { id: _self.newId };
        var su = $("#grid").jqGrid('addRowData', _self.newId, datarow, 'before', rowId);
        if(su) {
            $(this).parents('td').removeAttr('class');
            $(this).parents('tr').removeClass('selected-row ui-state-hover');
            $("#grid").jqGrid('resetSelection');
            _self.newId++;
        }
    });

    $("#grid").on("click", ".operating .ui-icon-pencil", function (t)
    {
        t.preventDefault();

        var e = $(this).parent().data("id");
        handle.operate("edit", e)
    });

    $('#grid').on("click", ".operating .ui-icon-trash", function (t)
    {
        t.preventDefault();

        var e = $(this).parent().data("id");

        handle.del(e)
    });
}


var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, type_id =api.data.type_id, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();
initEvent();