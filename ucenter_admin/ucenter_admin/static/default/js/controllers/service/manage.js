function init()
{
    typeof cRowId != "undefined" ? Public.ajaxPost("./index.php?ctl=Service_Idea&met=get", {
        idea_id: cRowId.idea_id
    }, function (rs)
    {
        200 == rs.status ? (rowData = rs.data, initField(), initEvent(), initGrid(rowData.links)) : parent.$.dialog({
            title: "系统提示",
            content: "获取意见数据失败，暂不能回复，请稍候重试",
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
    }) : (initField(), initEvent(), initGrid())
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
            vendor_name: "required;",
            vendor_number: "required;"
        },
        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text()
        },
        valid: function (form)
        {
            var a = "add" == oper ? "新增供应商" : "回复意见", b = getData(), c = b.firstLink || {};
            delete b.firstLink,

                Public.ajaxPost("./index.php?ctl=Service_Idea&typ=json&met=" + ("add" == oper ? "add" : "edit"), b, function (e)
                {
                    if (200 == e.status)
                    {
                        parent.parent.Public.tips({content: a + "成功！"});
                        callback && "function" == typeof callback && callback(e.data, oper, window)
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
    var data = cRowId ? {
        idea_id: cRowId.idea_id,
        title: $.trim($("#title").val()),
        idea: $.trim($("#idea").val()),
        creat_time: $.trim($("#creat_time").val()),
        creat_name: $.trim($("#creat_name").val()),
        respon: $.trim($("#respon").val())
    } : {
        idea_id: cRowId.idea_id,
        title: $.trim($("#title").val()),
        idea: $.trim($("#idea").val()),
        creat_time: $.trim($("#creat_time").val()),
        creat_name: $.trim($("#creat_name").val()),
        respon: $.trim($("#respon").val())
    };
    return data
}

function initField()
{
    console.info(rowData);
    if (rowData.idea_id)
    {
        $("#idea_id").val(rowData.idea_id);
        $("#title").val(rowData.title);
        $("#idea").val(rowData.idea);
        $("#creat_time").val(rowData.creat_time);
        $("#creat_name").val(rowData.creat_name);
        $("#respon").val(rowData.respon);
    }
}

function initEvent()
{
    $("#type").data("defItem",["vendor_type_id",rowData.vendor_type_id]);
    type = $("#type").combo({
        data: "./erp.php?ctl=Vendor_Type&met=queryAllType&typ=json",
        value: "vendor_type_id",
        text: "vendor_type_name",
        width: 210,
        ajaxOptions: {
            formatData: function (e)
            {
                return e.data.rows;
            }
        },
        defaultSelected: rowData.vendor_type_id ? $("#type").data("defItem") : void 0,
    }).getCombo();

    $("#level").data("defItem",["vendor_level_id",rowData.vendor_level_id]);
    level = $("#level").combo({
        data: "./erp.php?ctl=Vendor_Level&met=queryAllLevel&typ=json",
        value: "vendor_level_id",
        text: "vendor_level_name",
        width: 210,
        ajaxOptions: {
            formatData: function (e)
            {
                return e.data.rows;
            }
        },
        defaultSelected: rowData.vendor_level_id ? $("#level").data("defItem") : void 0,
    }).getCombo();

    $(".grid-wrap").on("click", ".ui-icon-ellipsis", function (a)
    {
        a.preventDefault();
        var b = $(this).siblings(),
            c = $(this).closest("tr"),
            d = c.data("addressInfo");
        parent.$.dialog({
            title: "联系地址",
            content: "url:./erp.php?ctl=Base_Address&met=index",
            data: {
                rowData: d, callback: function (a, d)
                {
                    if (a)
                    {
                        var e = {};
                        e.province = a.province || "",
                            e.city = a.city || "",
                            e.county = a.area || "",
                            e.address = a.address || "",
                            b.val(e.province + e.city + e.county + e.address),
                            c.data("addressInfo", e);
                    }
                    d.close()
                }
            },
            width: 640,
            height: 210,
            min: !1,
            max: !1,
            cache: !1,
            lock: !1
        })
    }),
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
function initGrid(links)
{
    if (links || (links = []), links.length < 4)
    {
        for (var b = 4 - links.length, c = 0; b > c; c++)
        {
            links.push({});
        }
    }
    links.push({}),
        $grid.jqGrid({
            data: links,
            datatype: "local",
            width: 600,
            gridview: !0,
            onselectrow: !1,
            colModel: [
                {
                    name: "vendor_contacter_name",
                    label: "联系人",
                    width: 50,
                    title: !1,
                    editable: !0
                },
                {
                    name: "vendor_contacter_mobile",
                    label: "手机",
                    width: 50,
                    title: !1,
                    editable: !0
                },
                {
                    name: "vendor_contacter_telephone",
                    label: "座机",
                    width: 50,
                    title: !1,
                    editable: !0
                },
                {
                    name: "vendor_contacter_address",
                    label: "联系地址",
                    width: 50,
                    title: !0,
                    formatter: addressFmt,
                    classes: "ui-ellipsis",
                    editable: !0,
                    edittype: "custom",
                    editoptions: {
                        custom_element: addressElem,
                        custom_value: addressValue,
                        handle: addressHandle,
                        trigger: "ui-icon-ellipsis"
                    }
                },
                {
                    name: "vendor_contacter_code",
                    label: "邮编",
                    width: 50,
                    title: !1,
                    editable: !0
                }],
            cmTemplate: {sortable: !1},
            shrinkToFit: !0,
            forceFit: !0,
            cellEdit: !0,
            cellsubmit: "clientArray",
            localReader: {root: "items", records: "records", repeatitems: !0, id: 'id'},
            loadComplete: function (link_data)
            {
                if ($grid.setGridHeight($grid.height() > 185 ? "185" : "auto"), $grid.setGridWidth(600), "add" != oper)
                {
                    if (!link_data || !link_data.items)
                    {
                        return void(linksIds = []);
                    }
                    linksIds = [];
                    for (var items = link_data.items, c = 0; c < items.length; c++)
                    {
                        var item = items[c];
                        if (item.id)
                        {
                            linksIds.push(Number(item.id));
                            var e = {
                                province: item.vendor_contacter_province,
                                city: item.vendor_contacter_city,
                                county: item.vendor_contacter_county,
                                address: item.vendor_contacter_address
                            };
                            $("#" + item.id).data("addressInfo", e);
                        }
                    }
                }
            },
            afterEditCell: function (a, b, c)
            {
                $("#" + a).find("input").val(c)
            },
            afterSaveCell: function (a, b, c)
            {
                if ("first" == b && (c = "boolean" == typeof c ? c ? "1" : "0" : c, "1" === c))
                {
                    for (var d = $grid.jqGrid("getDataIDs"), e = 0; e < d.length; e++)
                    {
                        var f = d[e];
                        f != a && $grid.jqGrid("setCell", f, "first", "0")
                    }
                }
            }
        })
}
function addressFmt(a, b, c)
{
    if (!c.vendor_contacter_address)
    {
        if(a)
            return a;
    }
    var d = {};
    return d.province = c.vendor_contacter_province || "",
        d.city = c.vendor_contacter_city || "",
        d.county = c.vendor_contacter_county || "",
        d.address = c.vendor_contacter_address || "",
        $("#" + c.id).data("addressInfo", d),
    d.province + d.city + d.county + d.address || "&#160;"
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

var curRow, curCol, curArrears, api = frameElement.api, oper = api.data.oper, cRowId = api.data.rowData, rowData = {}, linksIds = [], callback = api.data.callback, defaultPage = Public.getDefaultPage(), $grid = $("#grid"), $_form = $("#manage-form");
initPopBtns(), init();