urlParam = Public.urlParam();
var queryConditions = {
        id:urlParam.id,
        state:urlParam.tab
    },  
    hiddenAmount = false, 
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('order-list');//页面配置初始化
        this.initDom();
        this.loadGrid();            
        this.addEvent();
    },
    initDom: function(){
        this.$_searchName = $('#searchName');
        this.$_searchName.placeholder();
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
		{name: "order_virtual_code", label: "兑换码", align: "center",width: 100,sortable:false},
		{name: "order_create_time", label: "消费时间", align: "center",width: 140,sortable:true},
		{name: "order_id", label: "订单号", align: "center",width: 208,sortable:false},
		{name: "order_payment_amount", label: "消费金额", align: "center",width: 80,sortable:true},
		{name: "order_commission_fee", label: "佣金金额", align: "center",width: 80,sortable:true},
		{name: "buyer_user_name", label: "买家", align: "center",width: 100,sortable:false}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL + "?ctl=Operation_Settlement&met=getVirtual&typ=json",
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            height: 450,
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
              id: "os_id"
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
            var state = "查看";
	    var html_con = '<div class="operating" data-id="' + row.id + '"><a data-right="BU_QUERY" parentOpen="true" href="'+SITE_URL+'?ctl=Trade_Order&met=getOrderInfo&order_id='+row.id+'" rel="pageTab" tabid="return-detail" tabtxt="查看订单"><span class="ui-icon ui-icon-pencil" title="查看"></span></a></div>';
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
            queryConditions.order_id = $("#orderId").val();
            queryConditions.buyer_user_account = $("#buyerName").val();
            THISPAGE.reloadData(queryConditions);
        });

        $("#btn-refresh").click(function ()
        {
            queryConditions.order_id = '';
            queryConditions.buyer_user_account = '';
            THISPAGE.reloadData(queryConditions);
        });

        $("#btn-excel").click(function ()
        {
            var query = "";
            for (x in queryConditions)
            {
                query = query + "&" + x + "=" + queryConditions[x];
            }
            window.open(SITE_URL + "?ctl=Jump&met=index&ct=Operation_Settlement&mt=getVirtualExcel&debug=1"+query);
        });

        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};

$(function(){

    Public.pageTab();
    
    THISPAGE.init();
    
});





