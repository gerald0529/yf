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
        this.mod_PageConfig = Public.mod_PageConfig.init('complain-new-list');//页面配置初始化
        this.initDom();
        this.loadGrid();
        this.addEvent();
    },
    initDom: function ()
    {
        this.$_searchName = $('#search_name');
        this.$_searchName.placeholder('请输入相关数据...');
    },
    loadGrid: function ()
    {
        var gridWH = Public.setGrid(), _self = this;
        var colModel = [
            {name: 'operating', label: '操作', width: 40, fixed: true, formatter: operFmatter, align: "center"},
            {name: 'user_account_accuser', label: '投诉人', width: 100, align: "center"},
            {name: 'complain_content', label: '投诉内容', width: 444, align: "left"},
            {
                name: 'complain_image',
                label: '投诉图片',
                width: 200,
                align: 'center',
                formatter: online_imgFmt,
                classes: "complain_image"
            },
            {name: 'complain_datetime', label: '投诉时间', width: 150, align: "center"},
            {name: 'complain_subject_content', label: '投诉主题', width: 200, align: "center"},
            {name: 'user_account_accused', label: '被投商家', width: 150, align: "center"},
            {name: 'user_id_accuser', label: '投诉人ID', width: 100, align: "center"},
            {name: 'user_id_accused', label: '商家ID', width: 100, align: "center"}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url: SITE_URL + '?ctl=Trade_Complain&met=getComplainList&typ=json&state=' + complain_state,
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            height: Public.setGrid().h,
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
            forceFit: false,
            jsonReader: {
                root: "data.items",
                records: "data.records",
                repeatitems: false,
                total: "data.total",
                id: "complain_id"
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
            var html_con = '<div class="operating" data-id="' + row.complain_id + '"><span class="ui-icon ui-icon-pencil" title="处理"></span></div>';
            return html_con;
        };

        function online_imgFmt(val, opt, row)
        {
            if (val)
            {
                val = '<img src="' + val + '" height=60>';
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
            handle.operate("edit", e)
        });

        $('#search').click(function ()
        {
            queryConditions.search_name = _self.$_searchName.val();
            queryConditions.user_type = $source.getValue();
            THISPAGE.reloadData(queryConditions);
        });

        $("#btn-refresh").click(function ()
        {
            THISPAGE.reloadData('');
            _self.$_searchName.placeholder('请输入相关数据...');
            _self.$_searchName.val('');
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
        f = 'complain-progress';
        parent.tab.addTabItem({
            tabid: f,
            text: '投诉管理',
            url: SITE_URL + '?ctl=Trade_Complain&met=getComplainInfo&id=' + e + '&state=' + complain_state
        })
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
