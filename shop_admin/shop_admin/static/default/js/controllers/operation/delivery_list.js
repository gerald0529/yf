urlParam = Public.urlParam();
var queryConditions = {
        "dtyp":urlParam.dtyp
    },  
    hiddenAmount = false, 
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('delivery-list');//页面配置初始化
        //this.initDom();
        this.loadGrid();            
        this.addEvent();
    },
    initDom: function(){
        //this.$_settleId = $('#settleId');
        //this.$_settleId.placeholder();
        //this.$_shopName = $('#shopName');
        //this.$_shopName.placeholder();
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        var colModel = [
		{
		    name: "operating",
		    label:'操作',
		    width: 60,
		    fixed: !0,
		    align: "center",
		    formatter: operFmatter
		}, 
		{name: "user_account", label: "用户名", align: "center",width: 100,sortable:false},
		{name: "delivery_real_name", label: "真实姓名", align: "center",width: 100,sortable:false},
		{name: "delivery_name", label: "服务站名称", align: "center",width: 100,sortable:false},
		{name: "delivery_area", label: "所在地区", align: "center",width: 150,sortable:false},
		{name: "delivery_address", label: "详细地址", align: "left",width: 484,sortable:false},
		{name: "delivery_state_text", label: "状态", align: "center",width:100,sortable:false},
		{name: "delivery_apply_date", label: "申请时间", align: "center",width: 150,sortable:true}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL + "?ctl=Operation_Delivery&met=getDeliveryList&typ=json",
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            height: gridWH.h,
            altRows: true, //设置隔行显示
            gridview: true,
            multiselect: false,
            multiboxonly: true,
            colModel:colModel,
            cmTemplate: {sortable: false, title: false},
            page: 1, 
            sortname: 'number',    
            sortorder: "desc", 
            pager: "#page",  
            rowNum: 10,
            rowList:[10,20,50], 
            viewrecords: true,
            shrinkToFit: false,
            forceFit: false,
            jsonReader: {
              root: "data.items",
              records: "data.records",
              repeatitems : false,
              total : "data.total",
              id: "delivery_id"
            },
            loadError : function(xhr,st,err) {
                
            },
            ondblClickRow : function(rowid, iRow, iCol, e){
                $('#' + rowid).find('.ui-icon-pencil').trigger('click');
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
            var html_con = '<div class="operating" data-id="' + row.id + '"><a data-right="BU_QUERY" parentOpen="true" href="'+SITE_URL+'?ctl=Operation_Delivery&met=getDetail&id='+row.delivery_id+'" rel="pageTab" tabid="delivery-detail" tabtxt="查看自提站订单"><span class="ui-icon ui-icon-search" title="查看订单"></span></a><span class="ui-icon ui-icon-pencil" title="修改"></span></div>';
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

        //编辑
        $('#grid').on('click', '.ui-icon-pencil', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.operate("edit", e)
        });

        $('#search').click(function(){
            
            queryConditions.user_account = $('#user_account').val();
            queryConditions.delivery_real_name = $('#delivery_real_name').val();
            queryConditions.delivery_name = $('#delivery_name').val();
            THISPAGE.reloadData(queryConditions);
        });

        $("#btn-refresh").click(function ()
        {
            queryConditions.user_account = '';
            queryConditions.delivery_real_name = '';
            queryConditions.delivery_name = '';
            THISPAGE.reloadData(queryConditions);
        });

        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};

var handle = {
    operate: function (t, e)
    {
        var i = "修改服务站资料", a = {oper: t, id:e, callback: this.callback};
        console.info(a);
        $.dialog({
            title: i,
            content: "url:"+SITE_URL+"?ctl=Operation_Delivery&met=getDelivery&id="+e,
            data: a,
            width: 600,
            height:$(window).height(),
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
        a[t.member_id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.member_id, t);
            i && i.api.close();
            $("#grid").trigger("reloadGrid");
        }
    }
};

$(function(){

    Public.pageTab();

    THISPAGE.init();

});