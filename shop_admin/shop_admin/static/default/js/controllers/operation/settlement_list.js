urlParam = Public.urlParam();
var queryConditions = {
        "otyp":urlParam.otyp
    },  
    hiddenAmount = false, 
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('settlement-list');//页面配置初始化
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
		    width: 40,
		    fixed: !0,
		    align: "center",
		    formatter: operFmatter
		}, 
		{name: "os_id", label: "账单编号", align: "center",width:200,sortable:true},
		{name: "os_order_amount", label: "订单金额(含运费)", align: "center",width:100,sortable:true},
		{name: "os_shipping_amount", label: "运费", align: "center",width:100,sortable:true},
		{name: "os_redpacket_amount", label: "平台红包", align: "center",width:100,sortable:true},
		{name: "os_commis_amount", label: "收取佣金", align: "center",width:100,sortable:true},
		{name: "os_order_return_amount", label: "退单金额", align: "center",width:100,sortable:true},
		{name: "os_redpacket_return_amount", label: "退还红包金额", align: "center",width:100,sortable:true},
		{name: "os_commis_return_amount", label: "退还佣金", align: "center",width:100,sortable:true},
		{name: "os_shop_cost_amount", label: "店铺费用", align: "center",width:100,sortable:true},
		{name: "os_amount", label: "本期应结", align: "center",width:100,sortable:true},
		{name: "os_datetime", label: "出账日期", align: "center",width: 150,sortable:true},
		{name: "os_state_text", label: "账单状态", align: "center",width:100,sortable:false},
		{name: "shop_name", label: "商家名称", align: "center",width: 200,sortable:false},
		{name: "os_start_date", label: "开始日期", align: "center",width:150,sortable:true},
		{name: "os_end_date", label: "结束日期", align: "center",width:150,sortable:true}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL + "?ctl=Operation_Settlement&met=getSettleList&typ=json",
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            height:Public.setGrid().h,
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
			if(row.os_state_etext='wait_operate' || row.os_state_etext=='finish'){
			var state = '<span class="ui-icon ui-icon-search" title="查看详情"></span>';
			}else if(row.os_state_etext=='seller_comfirmed'){
			var state = '<span class="ui-icon ui-icon-search" title="审核"></span>';
			}else if(row.os_state_etext=='platform_comfirmed'){
			var state = '<span class="ui-icon ui-icon-search" title="付款完成"></span>';
			}
			var html_con = "<div class='operating' data-id='" + row.id + "'><a data-right='BU_QUERY' parentOpen='true' href='"+SITE_URL+"?ctl=Operation_Settlement&met=detail&id="+row.os_id+"' rel='pageTab' tabid='settlement-look' tabtxt='查看结算单'>"+state+"</a></div>";
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
            
            queryConditions.settleId = $('#settleId').val();
            queryConditions.shopName = $('#shopName').val();
            queryConditions.state = $source.getValue();
            queryConditions.start_time = $('#start_time').val();
            queryConditions.end_time = $('#end_time').val();
            THISPAGE.reloadData(queryConditions);
        });

        $("#btn-excel").click(function ()
        {
            var query = ""; 
            for (x in queryConditions)
            {
                query = query + "&" + x + "=" + queryConditions[x];
            }
            window.open(SITE_URL + "?ctl=Jump&met=index&ct=Operation_Settlement&mt=getSettleExcel&debug=1"+query);
        });

        $("#btn-refresh").click(function ()
        {
            queryConditions.settleId = '';
            queryConditions.shopName = '';
            queryConditions.state = '';
            queryConditions.start_time = '';
            queryConditions.end_time = '';
            THISPAGE.reloadData(queryConditions);
        });

        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};

$(function(){

    $('#start_time').datetimepicker({
        controlType: 'select',
        format:"Y-m-d",
        timepicker:false
    });

    $('#end_time').datetimepicker({
        controlType: 'select',
        format:"Y-m-d",
        timepicker:false
    });

    $source = $("#source").combo({
        data: [{
            id: "",
            name: "选择状态"
        },{
            id: "1",
            name: "已出账"
        },{
            id: "2",
            name: "商家已确认"
        },{
            id: "3",
            name: "平台已审核"
        },{
            id: "4",
            name: "结算完成"
        }],
        value: "id",
        text: "name",
        width: 110
    }).getCombo();

    Public.pageTab();

    THISPAGE.init();

});