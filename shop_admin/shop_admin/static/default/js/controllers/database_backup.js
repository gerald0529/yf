function initEvent() {
    $("#btn-backup").click(function(a) {
            a.preventDefault();
            do_backup();
        }
    ),
        $("#btn-refresh").click(function(a) {
                a.preventDefault(),
                    $("#grid").jqGrid("setGridParam", {
                        url: "./index.php?ctl=Database_Backup&met=getBackupList&typ=json",
                        datatype: "json"
                    }).trigger("reloadGrid")
            }
        ),
        $(window).resize(function() {
                Public.resizeGrid()
            }
        )
}
function dbbk_click(a,s)
{
    var i=$(a).parent().attr('data-id');
    do_action(s,i);
}
function do_backup()
{
    var a = parent.$.dialog.tips("备份中，请稍候...", 1e3, "loading.gif", !0);
    $.ajax({
        url:"./index.php?ctl=Database_Backup&typ=json&met=backup",
        type:'post',
        data:'action=backup',
        timeout:0,//兼容火狐
        success:function(b) {
            a.close();
            if(b && 200 == b.status)
            {
                parent.Public.tips({content: "备份成功！"});
                $("#btn-refresh").trigger('click');
            }
            else
            {
                parent.Public.tips({type: 1,content: "备份失败！"+b.msg});
            }
        }
    })
}
function do_action(s,i)
{
    var p={action:s,file:i},n=s=='restore'?'恢复':'删除';
    var a = parent.$.dialog.tips(n+"中，请稍候...", 1e3, "loading.gif", !0);
    Public.ajaxPost("./index.php?ctl=Database_Backup&typ=json&met="+s, p
        , function(b) {
            a.close();
            if(b && 200 == b.status)
            {
                parent.Public.tips({content: n+"成功！"});
                if(n=='删除')
                    $("#grid").jqGrid("delRowData", i);
            }
            else
            {
                parent.Public.tips({type: 1,content: n+"失败！"+b.msg});
            }
        }
    )
}
function initGrid() {
    var a = ["操作","文件名","大小","备份时间"]
        , b = [{
            name: "operate",
            width: 80,
            fixed: !0,
            align: "center",
            formatter: function (val, opt, row) {
                var html_con = '<div class="operating" data-id="' + row.file_name + '"><a href="javascript:void(0)" onclick="dbbk_click(this,\'restore\');" class="" >恢复</a><a style="margin-left:7px;"href="javascript:void(0)" class="" onclick="dbbk_click(this,\'delete\');" >删除</a></div>';
                return html_con;
            }
        },{
            name: "file_name",
            index: "file_name",
            width: 200
        },{
            name: "size",
            index: "size",
            align: "center",
            width: 100
        },{
            name: "time",
            index: "time",
            align: "center",
            width: 200
        }];
    $("#grid").jqGrid({
        url: "./index.php?ctl=Database_Backup&met=getBackupList&typ=json",
        datatype: "json",
        height: Public.setGrid().h,
        altRows: !0,
        gridview: !0,
        rowNum: 200,
        colNames: a,
        colModel: b,
        autowidth: !0,
        viewrecords: !0,
        cmTemplate: {
            sortable: !1,
            title: !1
        },
        page: 1,
        pager: "#page",
        shrinkToFit: !1,
        scroll: !0,
        jsonReader: {
            root: "data.items",
            records: "data.records",
            total:"data.total",
            repeatitems: !1,
            id: "file_name"
        },
        loadComplete: function(a) {
            if (a && 200 == a.status) {
                var b = {};
                a = a.data;
                for (var c = 0; c < a.items.length; c++) {
                    var d = a.items[c];
                    b[d.file_name] = d
                }
                $("#grid").data("gridData", b)
            } else {
                var e = 250 == a.status ? "没有数据！" : "获取数据失败！" + a.msg;
                parent.Public.tips({
                    type: 2,
                    content: e
                })
            }
        },
        loadError: function(a, b, c) {
            parent.Public.tips({
                type: 1,
                content: "操作失败了哦，请检查您的网络链接！"
            })
        }
    })
}
initEvent(),
    initGrid();