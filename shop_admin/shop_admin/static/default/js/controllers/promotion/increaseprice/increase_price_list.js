/**
 * Created by Administrator on 2016/5/15.
 */
var queryConditions = {
        increase_name:'',
        shop_name:''
    },
    hiddenAmount = false,
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('increase-list');//页面配置初始化
        this.initDom();
        this.loadGrid();
        this.addEvent();
    },
    initDom: function(){
        this.$_increase_name = $('#increase_name');
        this.$_shop_name = $('#shop_name');
        this.$_increase_name.placeholder();
        this.$_shop_name.placeholder();
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        var colModel = [
            {name:'operating', label:'操作', width:50, fixed:true, formatter:operFmatter, align:"center"},
            {name:'increase_name', label:'活动名称', width:200, align:"center"},
            {name:'shop_name', label:'店铺名称', width:200, align:"center","formatter": handle.linkShopFormatter},
            {name:'increase_start_time', label:'开始时间',  width:150, align:"center"},
            {name:'increase_end_time', label:'结束时间',  width:150, align:"center"},
            {name:'increase_state_label', label:'状态',  width:100, align:"center"}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url: SITE_URL + '?ctl=Promotion_ActIncrease&met=getIncreaseList&typ=json',
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            height: gridWH.h,
            altRows: true, //设置隔行显示
            gridview: true,
            multiboxonly: true,
            colModel:colModel,
            cmTemplate: {sortable: false, title: false},
            page: 1,
            sortname: 'number',
            sortorder: "desc",
            pager: "#page",
            rowNum: 100,
            rowList:[100,200,500],
            viewrecords: true,
            shrinkToFit: false,
            forceFit: true,
            jsonReader: {
                root: "data.items",
                records: "data.records",
                repeatitems : false,
                total : "data.total",
                id: "increase_id"
            },
            loadError : function(xhr,st,err) {

            },
            resizeStop: function(newwidth, index){
                THISPAGE.mod_PageConfig.setGridWidthByIndex(newwidth, index, 'grid');
            }
        }).navGrid('#page',{edit:false,add:false,del:false,search:false,refresh:false}).navButtonAdd('#page',{
            caption:"",
            buttonicon:"ui-icon-config",
            onClickButton: function(){
                THISPAGE.mod_PageConfig.config();
            },
            position:"last"
        });


        function operFmatter (val, opt, row) {
            var html_con = '<div class="operating" data-id="' + row.increase_id + '"><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-pencil" title="编辑"></span></div>';
            return html_con;
        };

        function online_imgFmt(val, opt, row){
            if(val)
            {
                val = '<img src="'+val+'" height=100>';
            }
            else
            {
                val='';
            }
            return val;
        }

    },
    reloadData: function(data){
        $("#grid").jqGrid('setGridParam',{postData: data}).trigger("reloadGrid");
    },
    addEvent: function(){
        var _self = this;
        //活动详情
        $('.grid-wrap').on('click', '.ui-icon-pencil', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.operate("detail", e)
        });

        //搜索
        $('#search').click(function(){
			queryConditions.increase_state = $source.getValue();
            queryConditions.increase_name = _self.$_increase_name.val();
            queryConditions.shop_name = _self.$_shop_name.val();
            THISPAGE.reloadData(queryConditions);
        });

        //刷新
        $("#btn-refresh").click(function ()
        {
            THISPAGE.reloadData('');
            _self.$_increase_name.val('');
            _self.$_shop_name.val('');
        });

        //删除
        $("#grid").on("click", ".operating .ui-icon-trash", function (t)
        {
            t.preventDefault();
            var e = $(this).parent().data("id");
            handle.del(e)
        });

        //跳转到店铺认证信息页面
        $('#grid').on('click', '.to-shop', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var shop_id = $(this).attr('data-id');
            $.dialog({
                title: '查看店铺信息',
                content: "url:"+SITE_URL + '?ctl=Shop_Manage&met=getShoplist&shop_id=' + shop_id,
                width: 1000,
                height: $(window).height(),
                max: !1,
                min: !1,
                cache: !1,
                lock: !0
            })
        });

        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};

var handle = {
    linkShopFormatter: function(val, opt, row) {
        return '<a href="javascript:void(0)"><span class="to-shop" data-id="' + row.shop_id + '">' + val + '</span></a>';
    },
    operate: function (t, e)
    {
        if ("detail" == t)
        {
            var i = "店铺加价购活动详情", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
            //console.info(a);
        }
        $.dialog({
            title: i,
            content: "url:"+ SITE_URL + '?ctl=Promotion_ActIncrease&met=getIncreaseInfo&typ=e&id=' + e,
            data: a,
            width: 670,
            height:410,
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
        a[t.increase_id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.increase_id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.increase_id, t, "last");
            i && i.api.close()
        }
    }, del: function (t)
    {
        $.dialog.confirm(_("删除的活动将不能恢复，请确认是否删除？"), function ()
        {
            Public.ajaxPost(SITE_URL + '?ctl=Promotion_ActIncrease&met=removeIncrease&typ=json', {increase_id: t}, function (e)
            {
                //alert(JSON.stringify(e));
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: _("活动删除成功！")});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: _("活动删除失败！") + e.msg})
                }
            })
        })
    }
};

$(function(){
	$source = $("#source").combo({
			data: [{
				id: "0",
				name: "活动状态"
			},{
				id: "1",
				name: "正常"
			},{
				id: "2",
				name: "已结束"
			}
			,{
				id: "3",
				name: "管理员关闭"
			}],
			value: "id",
			text: "name",
			width: 110
		}).getCombo();
		
    THISPAGE.init();
});
