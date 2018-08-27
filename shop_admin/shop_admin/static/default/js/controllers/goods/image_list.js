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
            var e = $(this).parent().data("id");
            handle.operate("edit", e)
        }
    });

    $("#grid").on("click", ".operating .ui-icon-trash", function (t)
    {
        t.preventDefault();
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
function initGrid( album_id, shop_id )
{
    function albumOperFatter (val, opt, row) {

        var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-trash" title="删除"></span></div>';

        return html_con;
    }

    function imageOperFatter ( val, opt, row )
    {
        var html_con = '<img width="240px" height="240px" src="' + row.upload_path + '" />';

        return html_con;
    }

    var t = ["操作", "相册名称", "图片ID", "图片", "图片地址", "图片大小", "上传时间"], e = [{
        name: "operate",
        width: 60,
        fixed: !0,
        align: "center",
        formatter: albumOperFatter
    },
        {name: "album_name", index: "album_name", width:200,  align: "center"},
        {name: "upload_id", index: "upload_id", width:100, align: "center"},
        {name: "image", index: "image", width:240, align: "center", formatter: imageOperFatter},
        {name: "upload_path", index: "image_address", width:500, align: "center"},
        {name: "upload_size", index: "image_size", width:150, align: "center"},
        {name: "upload_time", index: "upload_time", width:150, align: "center"}

    ];

    $("#grid").jqGrid({
        url: SITE_URL + "?ctl=Goods_Album&met=getImageList&typ=json&album_id=" + album_id + '&shop_id=' + shop_id,
        datatype: "json",
        height: Public.setGrid().h,
        colNames: t,
        colModel: e,
        autowidth: !0,
        pager: "#page",
        viewrecords: !0,
        cmTemplate: {sortable: !1, title: !1},
        page: 1,
        rowNum: 100,
        rowList: [100, 200, 500],
        shrinkToFit: !1,
        jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "upload_id"},
        loadComplete: function (t)
        {
            if (t && 200 == t.status)
            {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++)
                {
                    var a = t.items[i];
                    e[a.upload_id] = a;
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
    del: function (t)
    {
        $.dialog.confirm("删除的分类将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Goods_Album&met=removeImage&typ=json", {id: t}, function (e)
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
if ( window.location.href.indexOf('album_id') > -1 ) {

    var album_string = window.location.href.match(/album_id=\d+/),
        shop_string = window.location.href.match(/shop_id=\d+/);

    if ( album_string && album_string[0] && shop_string && shop_string[0] ) {

        var album_id = album_string[0].match(/\d+/),
            shop_id = shop_string[0].match(/\d+/);

        initEvent();
        initGrid( album_id, shop_id );
    }
}
