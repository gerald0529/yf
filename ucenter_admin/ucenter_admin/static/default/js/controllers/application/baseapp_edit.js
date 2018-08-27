function init()
{
    alert(cRowId.app_id);
    typeof cRowId != "undefined" ? Public.ajaxPost("./index.php?ctl=BaseApp_BaseApp&met=getApps&typ=json", {
        app_id: cRowId.app_id
    }, function (rs)
    {
        alert(cRowId.app_id);
        alert(rs.status);
        200 == rs.status ? (rowData = rs.data, initField(), initEvent()) : parent.$.dialog({
            title: "系统提示",
            content: "获取应用配置数据失败，暂不能修改供应商，请稍候重试",
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
            app_id: "required;"
            //vendor_number: "required;",
        },
        display: function (a)
        {
            return $(a).closest(".row-item").find("label").text()
        },
        valid: function (form)
        {
            var a = "add" == oper ? "新增应用配置" : "修改应用配置", b = getData(), c = b.firstLink || {};
            delete b.firstLink,

                Public.ajaxPost("./index.php?ctl=BaseApp_BaseApp&typ=json&met=" + ("add" == oper ? "add" : "edit"), b, function (e)
                {
                    if (200 == e.status)
                    {
                        parent.parent.Public.tips({content: a + "成功！"});
                        callback && "function" == typeof callback && callback(e.data, oper, window);
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
    var app_status = $.trim($('[name = app_status]:checked').val());

    var link_info = getEntriesData(), links = link_info.entriesData, data = cRowId ? {
        app_id: cRowId.app_id,
        app_name: $.trim($("#app_name").val()),
        app_type: $.trim($("#app_type").val()),
        //app_seq: $.trim($("#app_seq").val()),
        app_key: $.trim($("#app_key").val()),
        app_ip_list: $.trim($("#app_ip_list").val()),
        app_url: $.trim($("#app_url").val()),
        app_admin_url: $.trim($("#app_admin_url").val()),
        app_status: app_status


    } : {
        app_name: $.trim($("#app_name").val()),
        app_type: $.trim($("#app_type").val()),
        //app_seq: $.trim($("#app_seq").val()),
        app_key: $.trim($("#app_key").val()),
        app_ip_list: $.trim($("#app_ip_list").val()),
        app_url: $.trim($("#app_url").val()),
        app_admin_url: $.trim($("#app_admin_url").val()),
        app_status: app_status


    };


    return data.firstLink = link_info.firstLink, data
}

function getEntriesData()
{
    for (var a = {}, b = [], c = $grid.jqGrid("getDataIDs"), d = !1, e = 0, f = c.length; f > e; e++)
    {
        var g, h = c[e], i = $grid.jqGrid("getRowData", h);
        if ("" == i.app_name) break;
        g = {
            app_name: i.app_name,
            app_type: i.app_type,
            app_seq: i.app_seq,
            app_key: i.app_key,
            app_ip_list: i.app_ip_list,
            app_url: i.app_url,
            app_admin_url: i.app_admin_url,
            app_url_recharge: i.app_url_recharge,
            app_url_order: i.app_url_order,
            app_logo: i.app_logo,
            app_hosts: i.app_hosts,
            return_fields: i.return_fields

        };

    }
    return  a.entriesData = b, a
}
function initField()
{
    if (rowData.app_id)
    {
        $("#app_name").val(rowData.app_name);
        $("#app_type").val(rowData.app_type);
        $("#app_seq").val(rowData.app_seq);
        $("#app_key").val(rowData.app_key);
        $("#app_ip_list").val(rowData.app_ip_list);
        $("#app_url").val(rowData.app_url);
        $("#app_admin_url").val(rowData.app_admin_url);
        $("#app_url_recharge").val(rowData.app_url_recharge);
        $("#app_url_order").val(rowData.app_url_order);
        $("#app_logo").val(rowData.app_logo);
        $("#app_hosts").val(rowData.app_hosts);
        $("#return_fields").val(rowData.return_fields);

        if(rowData.app_status)
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
    }
}

function initEvent()
{
    $("#type").data("defItem",["vendor_type_id",rowData.vendor_type_id]);
    type = $("#type").combo({
        data: "./index.php?ctl=BaseApp_BaseApp&met=getApps&typ=json",
        value: "app_id",
        text: "app_name",
        width: 210,
        ajaxOptions: {
            formatData: function (e)
            {
                return e.data.rows;
            }
        },
        defaultSelected: rowData.vendor_type_id ? $("#type").data("defItem") : void 0,
    }).getCombo();

    $("#level").data("defItem",["app_id",rowData.app_id]);
    level = $("#level").combo({
        data: "./erp.php?ctl=Vendor_Level&met=queryAllLevel&typ=json",
        value: "app_id",
        text: "app_name",
        width: 210,
        ajaxOptions: {
            formatData: function (e)
            {
                return e.data.rows;
            }
        },
        defaultSelected: rowData.app_id ? $("#level").data("defItem") : void 0,
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
initPopBtns(),initField(),init();
console.info('sssss');
console.info(api.data);