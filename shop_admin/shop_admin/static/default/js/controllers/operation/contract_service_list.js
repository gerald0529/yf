var queryConditions = {},  
    hiddenAmount = false, 
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('contract-service-list');//页面配置初始化
        this.initDom();
        this.loadGrid();            
        this.addEvent();
    },
    initDom: function(){
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        var colModel = [
		{
		    name: "operating",
		    label:'操作',
		    width: 130,
		    align: "center",
		    formatter: operFmatter
		}, 
		{name: "shop_name", label: "店铺名称", align: "center",width: 200,sortable:false},
		{name: "contract_type_name", label: "保障服务", align: "center",width: 150,sortable:false},
		{name: "contract_cash", label: "保证金余额(元)", align: "center",width: 150,sortable:true},
		{name: "contract_state_text", label: "状态", align: "center",width: 100,sortable:false}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL + "?ctl=Operation_Contract&met=getServiceList&typ=json",
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
            forceFit: true,
            jsonReader: {
              root: "data.items",
              records: "data.records",
              repeatitems : false,
              total : "data.total",
              id: "constract_id"
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
            var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="编辑"></span><a data-right="BU_QUERY" parentOpen="true" href="'+SITE_URL+'?ctl=Operation_Contract&met=getDetail&id='+row.id+'" rel="pageTab" tabid="contract-detail" tabtxt="查看保障服务"><span class="ui-icon ui-icon-gear" title="查看详情"></span></a><span class="ui-icon ui-icon-plus" title="编辑保证金"></span><a data-right="BU_QUERY" parentOpen="true" href="'+SITE_URL+'?ctl=Operation_Contract&met=getDetail&cash=1&id='+row.id+'" rel="pageTab" tabid="contract-cash-log" tabtxt="查看保证金日志"><span class="ui-icon ui-icon-clipboard" title="保证金日志"></span></a></div>';
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

        $('#search').click(function(){

            queryConditions.shopName = $('#shopName').val();
            THISPAGE.reloadData(queryConditions);
        });
	//编辑
        $('#grid').on('click', '.ui-icon-pencil', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.operate("edit", e)
        });
		
		 $('#grid').on('click', '.ui-icon-plus', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.addcash("edit", e)
        });
	
        $("#btn-refresh").click(function ()
        {
            queryConditions.shopName ='';
            THISPAGE.reloadData(queryConditions);
            _self.$_searchName.val('');
        });

        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};

var handle = {
    operate: function (t, e)
    {
	var i = "编辑店铺保障服务", a = {oper: t, contract_id:e , callback: this.callback};
        $.dialog({
            title: i,
            content: "url:"+SITE_URL+"?ctl=Operation_Contract&met=manageShopContract&contract_id="+e,
            data: a,
            width: 500,
            height: 250,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
            })
    }, addcash: function (t, e)
    {
	var i = "编辑保证金", a = {oper: t, contract_id:e , callback: this.callback};
        $.dialog({
            title: i,
            content: "url:"+SITE_URL+"?ctl=Operation_Contract&met=manageContractCash&contract_id="+e,
            data: a,
            width: 600,
            height: 352,
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