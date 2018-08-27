urlParam = Public.urlParam();
var queryConditions = {
        id:urlParam.id
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
		    width: 30,
		    fixed: !0,
		    align: "center",
		    formatter: operFmatter
		},
        {name: "return_code", label: "退单编号", align: "center",width: 208,sortable:false},
		{name: "order_number", label: "订单编号", align: "center",width: 208,sortable:false},
		{name: "return_cash", label: "退款金额", align: "center",width: 80,sortable:true},
		{name: "return_commision_fee", label: "退还佣金", align: "center",width: 80,sortable:true},
		{name: "return_type_text", label: "类型", align: "center",width: 100,sortable:true},
		{name: "return_state_text", label: "审核状态", align: "center",width: 200,sortable:true},
		{name: "return_finish_time", label: "退款日期", align: "center",width: 150,sortable:true},
		{name: "buyer_user_account", label: "买家", align: "center",width: 100,sortable:true},
		{name: "seller_user_account", label: "店铺", align: "center",width: 100,sortable:true},
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL + "?ctl=Operation_Settlement&met=getReturn&typ=json",
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
	    var html_con = '<div class="operating" data-id="' + row.id + '"><a data-right="BU_QUERY" parentOpen="true" href="'+SITE_URL+'?ctl=Trade_Return&met=detail&id='+row.id+'" rel="pageTab" tabid="return-detail" tabtxt="查看退款"><span class="ui-icon ui-icon-pencil" title="查看"></span></a></div>';
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
            queryConditions.return_code = $("#returnCode").val();
            queryConditions.buyer_user_account = $("#buyerName").val();
            THISPAGE.reloadData(queryConditions);
        });

        $("#btn-refresh").click(function ()
        {
            queryConditions.order_id = '';
            queryConditions.return_code = '';
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
            window.open(SITE_URL + "?ctl=Jump&met=index&ct=Operation_Settlement&mt=getReturnExcel&debug=1"+query);
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





