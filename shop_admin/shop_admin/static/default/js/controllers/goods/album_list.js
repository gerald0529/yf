function initEvent()
{
    $("#btn-refresh").click(function (t)
    {
        t.preventDefault();
        $("#grid").trigger("reloadGrid")
    });

    $("#grid").on("click", ".operating .ui-icon-search", function (t)
    {
        t.preventDefault();
        if (Business.verifyRight("INVLOCTION_UPDATE"))
        {
            var e = $(this).parent().data("id"),
                shopId = $(this).parent().data("shop_id");
            handle.operate("edit", e, shopId)
        }
    });

    $("#grid").on("click", ".operating .ui-icon-trash", function (t)
    {
        t.preventDefault();
        if ( $(this).hasClass('ui-icon-disabled') ) {
            return false;
        }

        if (Business.verifyRight("INVLOCTION_DELETE"))
        {
            var e = $(this).parent().data("id");
            handle.del(e)
        }
    });

    $(window).resize(function ()
    {
        Public.resizeGrid()
    })
}
function initGrid()
{
    function albumOperFatter (val, opt, row) {

        var html_con;

        if ( row.is_default ) {
            html_con = '<div class="operating" data-shop_id="' + row.shop_id + '" data-id="' + row.album_id + '"><span class="ui-icon ui-icon-search" title="查看"></span><span class="ui-icon ui-icon-trash ui-icon-disabled" title="删除"></span></div>';
        } else {
            html_con = '<div class="operating" data-shop_id="' + row.shop_id + '" data-id="' + row.album_id + '"><span class="ui-icon ui-icon-search" title="查看"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
        }
        return html_con;
    }

    var t = ["操作", "相册ID", "相册名称", "店铺ID", "店铺名称", "图片数量"], e = [{
        name: "operate",
        width: 60,
        fixed: !0,
        align: "center",
        formatter: albumOperFatter
    },
        {name: "album_id", index: "album_id", width:100,  align: "center"},
        {name: "album_desc", index: "album_desc", width:200, align: "center"},
        {name: "shop_id", index: "shop_id", width:100, align: "center"},
        {name: "shop_name", index: "shop_name", width:200, align: "center"},
        {name: "album_num", index: "album_num", width:100, align: "center"}

    ];

    $("#grid").jqGrid({
        url: SITE_URL + "?ctl=Goods_Album&met=getAlbumList&typ=json",
        datatype: "json",
        height: Public.setGrid().h,
        colNames: t,
        colModel: e,
        autowidth: !0,
        pager: "#page",
        viewrecords: !0,
        cmTemplate: {sortable: !1, title: !1},
        page: 1,
        rowNum: 10,
        rowList: [10, 20, 50],
        shrinkToFit: false,
        forceFit: true,
        jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "album_id"},
        loadComplete: function (t)
        {
            if (t && 200 == t.status)
            {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++)
                {
                    var a = t.items[i];
                    e[a.album_id] = a;
                }
                $("#grid").data("gridData", e);
                0 == t.items.length && parent.Public.tips({type: 2, content: "没有数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取数据失败！" + t.msg})
            }
        },
        loadError: function ()
        {
            parent.Public.tips({type: 1, content: "操作失败了哦，请检查您的网络链接！"})
        }
    })
}

var handle = {
    operate: function (t, e, shopId)
    {
        if ("edit" == t)
        {
            var i = "图片列表", a = {oper: t, callback: this.callback};

            var urlS = 'album_id=' + e + '&shop_id=' + shopId;

            parent.tab.addTabItem({
                tabid: 'Album' + e,
                text: i,
                url: SITE_URL + '?ctl=Goods_Album&met=image&' + urlS
            })
        }
    }, callback: function (t, e, i)
    {
        var a = $("#grid").data("gridData");
        if (!a)
        {
            a = {};
            $("#grid").data("gridData", a)
        }
        a[t.id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.id, t, "last");
            i && i.api.close()
        }
    }, del: function (t)
    {
        $.dialog.confirm("删除的分类将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Goods_Album&met=remove&typ=json", {id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "删除失败" + e.msg})
                }
            })
        })
    }
};
initEvent();
initGrid();