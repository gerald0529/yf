var queryConditions = {
        cardName: ''
    },
    hiddenAmount = false,
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function (data)
    {
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT)
        {
            hiddenAmount = true;
        }
        ;
        this.mod_PageConfig = Public.mod_PageConfig.init('complain-subject-list');//页面配置初始化
        this.initDom();
        this.loadGrid();
        this.addEvent();
    },
    initDom: function ()
    {
        this.$_searchName = $('#searchName');
        this.$_searchName.placeholder();
    },
    loadGrid: function ()
    {
        var gridWH = Public.setGrid(), _self = this;
        var colModel = [
            {name: 'operating', label: '操作', width: 40, fixed: true, formatter: operFmatter, align: "center"},
            {name: 'complain_subject_content', label: '投诉主题', width: 200, align: "center"},
            {name: 'complain_subject_desc', label: '投诉主题描述', width: 1000, align: "left"}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url: SITE_URL + '?ctl=Trade_Complain&met=getComplainSubjectList&typ=json&state=1',
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            height: gridWH.h,
            altRows: true, //设置隔行显示
            gridview: true,
            multiselect: false,
            multiboxonly: true,
            colModel: colModel,
            cmTemplate: {sortable: false, title: false},
            page: 1,
            sortname: 'number',
            sortorder: "desc",
            pager: "#page",
            rowNum: 100,
            rowList: [100, 200, 500],
            viewrecords: true,
            shrinkToFit: false,
            forceFit: true,
            jsonReader: {
                root: "data.items",
                records: "data.records",
                repeatitems: false,
                total: "data.total",
                id: "complains_subject_id"
            },
            loadError: function (xhr, st, err)
            {

            },
            ondblClickRow: function (rowid, iRow, iCol, e)
            {
                $('#' + rowid).find('.ui-icon-pencil').trigger('click');
            },
            resizeStop: function (newwidth, index)
            {
                THISPAGE.mod_PageConfig.setGridWidthByIndex(newwidth, index, 'grid');
            }
        }).navGrid('#page', {
            edit: false,
            add: false,
            del: false,
            search: false,
            refresh: false
        }).navButtonAdd('#page', {
            caption: "",
            buttonicon: "ui-icon-config",
            onClickButton: function ()
            {
                THISPAGE.mod_PageConfig.config();
            },
            position: "last"
        });


        function operFmatter(val, opt, row)
        {
            var html_con = '<div class="operating" data-id="' + row.complain_subject_id + '"><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
            return html_con;
        };

        function online_imgFmt(val, opt, row)
        {
            if (val)
            {
                val = '<img src="' + val + '" height=100>';
            }
            else
            {
                val = '';
            }
            return val;
        }

    },
    reloadData: function (data)
    {
        $("#grid").jqGrid('setGridParam', {postData: data}).trigger("reloadGrid");
    },
    addEvent: function ()
    {
        var _self = this;
        //编辑
        $('.grid-wrap').on('click', '.ui-icon-pencil', function (e)
        {
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.operate("edit", e);
        });

        //删除
        $('.grid-wrap').on('click', '.ui-icon-trash', function (e)
        {
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.del(e)
        });

        //新增
        $("#add").click(function ()
        {
            handle.operate("add")
        });

        $('#search').click(function ()
        {
            queryConditions.searchName = _self.$_searchName.val() === '请输入相关数据...' ? '' : _self.$_searchName.val();
            queryConditions.userType = $source.getValue();
            THISPAGE.reloadData(queryConditions);
        });

        $("#btn-refresh").click(function ()
        {
            THISPAGE.reloadData('');
            _self.$_searchName.val('请输入相关数据...');
        });

        $(window).resize(function ()
        {
            Public.resizeGrid();
        });
    }
};
var handle = {
    operate: function (t, e)
    {
        if ("add" == t)
        {
            var i = "新增投诉主题", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "修改投诉主题", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
        }
        $.dialog({
            title: i,
            content: 'url:' + SITE_URL + '?ctl=Trade_Complain&met=subject_manage',
            data: a,
            width: 550,
            height: 270,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
    }, callback: function (t, e, i)
    {
        $("#btn-refresh").trigger('click');
        i && i.api.close();
    }, del: function (a)
    {
        $.dialog.confirm("删除后将不能恢复，请确认是否删除？", function ()
            {
                Public.ajaxPost(SITE_URL + '?ctl=Trade_Complain&met=delComplainSubjectById&typ=json', {
                        complain_subject_id: a
                    }, function (b)
                    {
                        b && 200 == b.status ? (parent.Public.tips({
                            content: "删除成功！"
                        }),
                            $("#grid").jqGrid("delRowData", a)) : parent.Public.tips({
                            type: 1,
                            content: "删除失败！" + b.msg
                        })
                        $("#btn-refresh").trigger('click');
                    }
                )
            }
        )
    }
};
$(function ()
{
    $source = $("#source").combo({
        data: [{
            id: "0",
            name: "投诉人"
        }, {
            id: "1",
            name: "被投商家"
        }],
        value: "id",
        text: "name",
        width: 110
    }).getCombo();

    THISPAGE.init();

});
