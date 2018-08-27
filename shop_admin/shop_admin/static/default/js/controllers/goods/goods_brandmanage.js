function init()
{
    typeof cRowId != "undefined" ? Public.ajaxPost(SITE_URL +"?ctl=Goods_Brand&met=getBrand&typ=json", {
        brand_id: cRowId.brand_id
    }, function (rs)
    {
        200 == rs.status ? (rowData = rs.data, initField(), initEvent()) : parent.$.dialog({
            title: "系统提示",
            content: "获取品牌数据失败，暂不能修改品牌，请稍候重试",
            icon: "alert.gif",
            max: !1,
            min: !1,
            cache: !1,
            lock: !0,
            ok: "确定",
            ok: function ()
            {
                return !0
            },
            close: function ()
            {
                api.close()
            }
        })
    }) : (initField(), initEvent())
}
function initPopBtns()
{
    var a = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: a[0], focus: !0, callback: function ()
        {
            return cancleGridEdit(), $_form.trigger("validate"), !1
        }
    }, {id: "cancel", name: a[1]})
}
function initValidator()
{
    $_form.validator({
        messages: {
            required: "请填写{0}"
        },
        fields: {
            brand_name: "required;"
        },
        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text()
        },
        valid: function (form)
        {
            var a = "add" == oper ? "新增品牌" : "修改品牌", b = getData();

                Public.ajaxPost(SITE_URL +"?ctl=Goods_Brand&typ=json&met=" + ("add" == oper ? "add" : "edit"), b, function (e)
                {
                    if (200 == e.status)
                    {
                        parent.parent.Public.tips({content: a + "成功！"});
                        callback && "function" == typeof callback && callback(e.data, oper, window,$.trim($('[name = brand_enable]:checked').val()))

                    }
                    else
                    {
                        parent.parent.Public.tips({type: 1, content: a + "失败！" + e.msg})
                    }
                })
        },
        ignore: ":hidden",
        theme: "yellow_bottom",
        timely: 1,
        stopOnError: !0
    })
}

function getData()
{
    var cat_id = categoryTree.getValue()
    var data = {
        brand_name: $.trim($("#brand_name").val()),
        cat_id: cat_id,
        brand_pic: $.trim($("#brand_pic").attr('src')),
        brand_show_type: $.trim($('[name = brand_show_type]:checked').val()),
        brand_recommend: $.trim($('[name = brand_recommend]:checked').val()),
        brand_enable: $.trim($('[name = brand_enable]:checked').val()),
        brand_displayorder: $.trim($("#brand_displayorder").val()),
        brand_pic: $.trim($("#brand_logo").val())
    };
     cRowId ?data['id'] = cRowId['id']: '';
    return data
}

function initField()
{
    var defaultPage = Public.getDefaultPage();
    $.each(defaultPage.SYSTEM.goodsCatInfo, function(key,val){
        if(rowData.cat_id!=0)
        {
            if(val['cat_id']==rowData.cat_id)
            {
                var cat_name = val['cat_name'];
                $("#cat_name").val(cat_name);
            }
        }
    });
    if (rowData.id)
    {
        $("#brand_name").val(rowData.brand_name);

        $("#brand_image").attr('src',rowData.brand_pic);
        $("#brand_logo").val(rowData.brand_pic);
        //$("input[name='brand_show_type']").attr('checked',rowData.brand_show_type);
        if(rowData.brand_show_type != 0)
        {
            $("#brand_show_type1").attr('checked', true);
            $("#brand_show_type0").attr('checked', false);
            $('[for="brand_show_type1"]').addClass('selected');
            $('[for="brand_show_type0"]').removeClass('selected');
        }
        else
        {
            $("#brand_show_type1").attr('checked', false);
            $("#brand_show_type0").attr('checked', true);
            $('[for="brand_show_type1"]').removeClass('selected');
            $('[for="brand_show_type0"]').addClass('selected');
        }
        if(rowData.brand_recommend)
        {
            $("#brand_recommend1").attr('checked', true);
            $("#brand_recommend0").attr('checked', false);
            $('[for="brand_recommend1"]').addClass('selected');
            $('[for="brand_recommend0"]').removeClass('selected');
        }
        else
        {
            $("#brand_recommend1").attr('checked', false);
            $("#brand_recommend0").attr('checked', true);
            $('[for="brand_recommend1"]').removeClass('selected');
            $('[for="brand_recommend0"]').addClass('selected');
        }
        if(rowData.brand_enable)
        {
            $("#brand_enable1").attr('checked', true);
            $("#brand_enable0").attr('checked', false);
            $('[for="brand_enable1"]').addClass('selected');
            $('[for="brand_enable0"]').removeClass('selected');
        }
        else
        {
            $("#brand_enable1").attr('checked', false);
            $("#brand_enable0").attr('checked', true);
            $('[for="brand_enable1"]').removeClass('selected');
            $('[for="brand_enable0"]').addClass('selected');
        }
        $("#brand_displayorder").val(rowData.brand_displayorder);
    }
}

function initEvent()
{
    $("#type").data("defItem",["vendor_type_id",rowData.vendor_type_id]);
    type = $("#type").combo({
        data: "./erp.php?ctl=Vendor_Type&met=queryAllType&typ=json",
        value: "brand_type_id",
        text: "brand_type_name",
        width: 210,
        ajaxOptions: {
            formatData: function (e)
            {
                return e.data.rows;
            }
        },
        defaultSelected: rowData.brand_type_id ? $("#type").data("defItem") : void 0
    }).getCombo();

        $(document).on("click.cancle", function (a)
        {
            var b = a.target || a.srcElement;
            !$(b).closest("#grid").length > 0 && cancleGridEdit()
        }), bindEventForEnterKey(), initValidator()
}

function bindEventForEnterKey()
{
    Public.bindEnterSkip($("#base-form"), function ()
    {
        $("#grid tr.jqgrow:eq(0) td:eq(0)").trigger("click")
    })
}

function addressElem()
{
    var a = $(".address")[0];
    return a
}
function addressValue(a, b, c)
{
    if ("get" === b)
    {
        var d = $.trim($(".address").val());
        return "" !== d ? d : ""
    }
    "set" === b && $("input", a).val(c)
}
function addressHandle()
{
    $(".hideFile").append($(".address").val("").unbind("focus.once"))
}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}

function initFilter()
{
    //查询条件
    Business.filterBrand();

    //商品类别
    var opts = {
        width : 200,
        //inputWidth : (SYSTEM.enableStorage ? 145 : 208),
        inputWidth : 145,
        defaultSelectValue : '-1',
        //defaultSelectValue : rowData.categoryId || '',
        showRoot : false
    }

    categoryTree = Public.categoryTree($('#cat_name'), opts);

}

var curRow, curCol, curArrears, api = frameElement.api, oper = api.data.oper, cRowId = api.data.rowData, rowData = {}, linksIds = [], callback = api.data.callback, defaultPage = Public.getDefaultPage(), $grid = $("#grid"), $_form = $("#manage-form"),menu = api.data.menu;
initPopBtns(),initFilter(), init();