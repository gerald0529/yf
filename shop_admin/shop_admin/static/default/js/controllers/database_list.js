function initEvent() {
    $("#btn-repair").click(function(a) {
            a.preventDefault();
            if($('.cbox:checked').length<1)
            {
                parent.Public.tips({
                    type: 1,
                    content: '没有选择数据表！'
                });
            }
            else
            {
                postData('repair','table','','');
            }
        }
    ),
        $("#btn-optimize").click(function(a) {
                a.preventDefault();
                if($('.cbox:checked').length<1)
                {
                    parent.Public.tips({
                        type: 1,
                        content: '没有选择数据表！'
                    });
                }
                else
                {
                    postData('optimize','table','','');
                }
            }
        ),
        $("#btn-backup").click(function(a) {
            a.preventDefault();
            if($('.cbox:checked').length<1)
            {
                parent.Public.tips({
                    type: 1,
                    content: '没有选择数据表！'
                });
            }
            else
            {
                postData('backup','table','','');
            }
        }
    ),
        $(window).resize(function() {
                Public.resizeGrid()
            }
        )
}
function dbmt_click(a,s)
{
    var i=$(a).parent().parent().parent().attr('id'),
        e=$(a).parent().parent().next().next().html();
    postData(s,'row',i,e);
}
function postData(t,b,m,e)
{
    var dat_str='{"action":"'+t+'","tables":[',n=t=='repair'?'修复/整理':'优化';
    if(b=='table')
    {
        for(var i=0;i<$('.cbox:checked').length;i++)
        {
            dat_str+='{"name":"'+$('.cbox:checked').eq(i).parent().parent().attr('id')+'","engine":"'+$('.cbox:checked').eq(i).parent().next().next().next().html()+'"},';
        }
        dat_str=dat_str.substr(0,dat_str.length-1);
    }
    else
        dat_str+='{"name":"'+m+'","engine":"'+e+'"}';

    dat_str+=']}';
    if(t=='backup'){
        Public.ajaxPost("./index.php?ctl=Database_Backup&typ=json&met=backup", {
                data: dat_str,
                action: t
            }, function(b) {
                if(b && 200 == b.status)
                {
                    parent.Public.tips({content: "备份成功！"});
                }
                else
                {
                    parent.Public.tips({type: 1,content: "备份失败！"+b.msg});
                }
            }
        )
    }else{
        Public.ajaxPost("./index.php?ctl=Database_Maintain&typ=json&met=manage", {
                data: dat_str
            }, function(b) {
                if(b && 200 == b.status)
                {
                    parent.Public.tips({content: n+"成功！"});
                }
                else
                {
                    parent.Public.tips({type: 1,content: n+"失败！"+b.msg});
                }
            }
        )
    }
}
function initGrid() {
    var a = ["操作","表名","引擎","编码","记录数","大小(k)",'备注']
        , b = [{
            name: "operate",
            width: 120,
            fixed: !0,
            align: "center",
            formatter: function (val, opt, row) {
                var str='修复';
                if(row.Engine=='InnoDB') str='整理';
                var html_con = '<div class="operating" data-id="' + row.id + '"><a href="javascript:void(0)" onclick="dbmt_click(this,\'repair\');" class="dbmt_repair" >'+str+'</a><a style="margin-left:7px;"href="javascript:void(0)" class="dbmt_optimize" onclick="dbmt_click(this,\'optimize\');" >优化</a><a style="margin-left:8px;"href="javascript:void(0)" class="dbmt_optimize" onclick="dbmt_click(this,\'backup\');" >备份</a></div>';
                return html_con;
            }
        },{
            name: "Name",
            index: "Name",
            width: 200
        },{
            name: "Engine",
            index: "Engine",
            align: "center",
            width: 100
        },{
            name: "Collation",
            index: "Collation",
            align: "center",
            width: 200
        },{
            name: "Rows",
            index: "Rows",
            align: "center",
            width: 100
        },{
            name: "Data_length",
            align: "center",
            formatter: function (val, opt, row) {
                if(!!row.Data_length)
                {
                    return (row.Data_length/1024).toFixed(2);
                }
                else
                    return 0;
            },
            width: 100
        },{
            name: "Comment",
            index: "Comment",
            align: "left",
            width: 300
        }];
    $("#grid").jqGrid({
        url: SITE_URL+"?ctl=Database_Maintain&met=TableList&typ=json",
        datatype: "json",
        height: Public.setGrid().h,
        altRows: !0,
        multiselect: !0,
        gridview: !0,
        rowNum: 200,
        colNames: a,
        colModel: b,
        shrinkToFit: false,
        forceFit: true,
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
            id: "Name"
        },
        loadComplete: function(a) {
            if (a && 200 == a.status) {
                var b = {};
                a = a.data;
                for (var c = 0; c < a.items.length; c++) {
                    var d = a.items[c];
                    b[d.Name] = d
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