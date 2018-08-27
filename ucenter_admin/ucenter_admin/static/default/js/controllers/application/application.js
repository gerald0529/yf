SYSTEM = system = parent.SYSTEM;
function initDom() {
    var defaultPage = Public.getDefaultPage();
    defaultPage.SYSTEM = defaultPage.SYSTEM || {};
    defaultPage.SYSTEM.categoryInfo = defaultPage.SYSTEM.categoryInfo || {};

    this.$_matchCon = $('#matchCon'),
        this.$_beginDate = $('#begin_date').val(system.beginDate),
        this.$_endDate = $('#end_date').val(system.endDate),
        this.$_matchCon.placeholder(),
        this.$_beginDate.datepicker(),
        this.$_endDate.datepicker()

};

function initEvent()
{
    $_matchCon = $("#matchCon"),
        $_matchCon.placeholder(),
        $("#search").on("click", function (a)
        {
            a.preventDefault();
            var b = "输入应用配置名称查询" === $_matchCon.val() ? "" : $.trim($_matchCon.val());
            $("#grid").jqGrid("setGridParam", {page: 1, postData: {skey: b}}).trigger("reloadGrid")
        });

    $("#export").click(function (t)
    {
        var b = "按应用配置编号，应用配置名称" === $_matchCon.val() ? "" : $.trim($_matchCon.val()),
            d = b ? '&matchCon=' + b : '';
        var f = './erp.php?ctl=Vendor_Base&typ=e&met=export' + d;
        $(this).attr('href', f)
    });

    $("#import").click(function (t)
    {
        var b = "按应用配置编号，应用配置名称" === $_matchCon.val() ? "" : $.trim($_matchCon.val()),
            d = b ? '&matchCon=' + b : '';
        var f = './erp.php?ctl=Vendor_Base&typ=e&met=export' + d;
        $(this).attr('href', f)
    });

    $("#btn-add").click(function (t)
    {
        t.preventDefault();
        Business.verifyRight("INVLOCTION_ADD") && handle.operate("add")
    });

    $("#btn-refresh").click(function (t)
    {
        t.preventDefault();
        $("#grid").trigger("reloadGrid")
    });

    $("#grid").on("click", ".operating .ui-icon-pencil", function (t)
    {
        t.preventDefault();
        if (Business.verifyRight("INVLOCTION_UPDATE"))
        {
            var e = $(this).parent().data("id");

            handle.operate("edit", e)
        }
    });
    $(window).resize(function ()
    {
        Public.resizeGrid()
    })
}

//操作项格式化，适用于有“修改、删除”操作的表格


function initGrid()
{

    var t = ["操作", "服务ID", "服务名称", "服务类型", "服务密钥", "服务 IP 列表", "服务网址","后台网址", "是否启用"/*,"网址补给","检查订单是否存在的url地址","图片地址","域名列表","返回字段"*/];
    e = [{
        name: "operate",
        width: 60,
        //fixed: !0,
        align: "center",
        formatter: function (val, opt, row)
        {
            var nav_str = '';
            var add_str = '';

            var html_con = '<div class="operating" data-id="' + row.app_id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span></div>';

            return html_con;
        }
    },
        {name: "app_id", index: "app_id", width: 50, align: "center",},
        {name: "app_name", index: "app_name", width: 200, align: "center",},
        {name: "app_type", index: "app_type", width: 100, align: "center",},
        {name: "app_key", index: "app_key", width: 250, align: "left",},
        {name: "app_ip_list", index: "app_ip_list", width: 100, align: "center",},
        {name: "app_url", index: "app_url", width: 400, align: "letf",},
        {name: "app_admin_url", index: "app_admin_url", width: 400, align: "letf",},
        {name: "app_status", index: "app_status", width: 60,
            align: "center",
            formatter: function (val, opt, row)
            {
                var html_con = val == 1 ? '启用' : '禁用';

                return html_con;
            }}];

    var grid_row = Public.setGrid();
    $("#grid").jqGrid({
        url: "./index.php?ctl=BaseApp_BaseApp&met=getBaseAppList&typ=json",
        width: grid_row.w,
        height: grid_row.h,
        datatype: "json",
        //height: Public.setGrid().h,
        colNames: t,
        colModel: e,
       autowidth: true,
        forceFit: false,
        pager: "#page",
        viewrecords: !0,
        cmTemplate: {sortable: !1, title: !1},
        shrinkToFit: !1,
        jsonReader: {root: "data.rows", id: "app_id"},
        loadComplete: function (t)
        {
            if (t && 200 == t.status)
            {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.rows.length; i++)
                {
                    var a = t.rows[i];
                    e[a.app_id] = a;
                }
                $("#grid").data("gridData", e);
                0 == t.rows.length && parent.Public.tips({type: 2, content: "没有应用设置数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取应用设置数据失败！" + t.msg})
            }
        },
        loadError: function ()
        {
            parent.Public.tips({type: 1, content: "操作失败了哦，请检查您的网络链接！"})
        }
    })
}

var handle = {
    operate: function (t, e)
    {
        if ("add" == t)
        {
            var i = "新增应用设置", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "修改应用设置", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
        }
        $.dialog({
            title: i,
            content: "url:./index.php?ctl=BaseApp_BaseApp&met=editApps",
            data: a,
            width: 650,
            height: 420,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
    }, callback: function (t, e, i)
    {
        var a = $("#grid").data("gridData");
        if (!a)
        {
            a = {};
            $("#grid").data("gridData", a)
        }
        a[t.app_id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.app_id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.app_id, t, "last");
            i && i.api.close()
        }

        $("#grid").trigger("reloadGrid")
    }
};
initDom();
initEvent();
initGrid();