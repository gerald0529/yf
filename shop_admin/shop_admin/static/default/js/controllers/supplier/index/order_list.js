if ( window.location.href.indexOf('shop_id') > -1 ) 
{ 
	var apply_string = window.location.href.match(/shop_id=\d+/); 
	if ( apply_string && apply_string[0]) 
	{ 
	var shop_id = apply_string[0].match(/\d+/); 
	} 
}
var queryConditions = {
        cardName: ''
    },  
    hiddenAmount = false, 
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('complain-new-list');//页面配置初始化
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
            {name:'order_id', label:'订单编号', width:120,align:"center"},
            {name:'buyer_user_name', label:'买家账号',  width:100, align:"center"},
            {name:'order_receiver_name', label:'收货人', width:120,align:'center'},
            {name:'payment_name', label:'支付名称', width:100,align:"center"},
            {name:'order_message', label:'买家备注',  width:120, align:"center"},
            {name:'order_payment_amount', label:'订单金额',  width:120, align:"center"},
            {name:'order_date', label:'下单日期',  width:150, align:"center"},
            {name:'order_status', label:'订单状态',  width:100, align:"center","formatter":order_statu}
          
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL +  "?ctl=Supplier_Manage&met=distributor_order&shop_id="+shop_id+"&typ=json",
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
              id: "shop_id"
            },
            loadError : function(xhr,st,err) {
                
            },

            resizeStop: function(newwidth, index){
                THISPAGE.mod_PageConfig.setGridWidthByIndex(newwidth, index, 'grid');
            },loadComplete: function(data) { 
            	$('#order_total').html(data.data.pay_total);
			}
        }).navGrid('#page',{edit:false,add:false,del:false,search:false,refresh:false}).navButtonAdd('#page',{
            caption:"",   
            buttonicon:"ui-icon-config",   
            onClickButton: function(){
                THISPAGE.mod_PageConfig.config();
            },   
            position:"last"  
        });
        
    function order_statu(val, opt, row){
    	var str = '';
    	if(row.order_status == 1){
    		str = '待付款';
    	}else if(row.order_status == 2){
    		str = '已付款';
    	}else if(row.order_status == 3){
    		str = '待发货';
    	}else if(row.order_status == 4){
    		str = '已发货';
    	}else if(row.order_status == 5){
    		str = '已签收';
    	}else if(row.order_status == 6){
    		str = '已完成';
    	}else if(row.order_status == 7){
    		str = '已取消';
    	}else if(row.order_status == 8){
    		str = '退款中';
    	}else if(row.order_status == 9){
    		str  = '退款完成';
    	}
    	return str;
    }

    },
    reloadData: function(data){
        $("#grid").jqGrid('setGridParam',{postData: data}).trigger("reloadGrid");
    },
    addEvent: function(){
        var _self = this;

        $('#search').click(function(){
            queryConditions.search_name = _self.$_searchName.val() === '请输入相关数据...' ? '' : _self.$_searchName.val();
            queryConditions.user_type = $source.getValue();
            queryConditions.shop_class = $shop_class.getValue();
            THISPAGE.reloadData(queryConditions);
        });

        $("#btn-refresh").click(function ()
        {
            THISPAGE.reloadData('');
            _self.$_searchName.val('请输入相关数据...');
        });

        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};
var handle = {
      imgFmt: function (val, opt, row)
    {      
        if (row.common_image)
        {
            val = '<img src="' + row.common_image + '" width="100px">';
        }
        else
        {
            val = '&#160;';
        }
        return val;
    }
};
   function testF(){ 
      window.location.reload(); 
}
$(function(){
    $source = $("#source").combo({
        data: [{
            id: "0",
            name: "店主账号"
        },{
            id: "1",
            name: "店铺名称"
        }],
        value: "id",
        text: "name",
        width: 110
    }).getCombo();

    $.get("./index.php?ctl=Shop_Class&met=shopClass&typ=json", function(result){
        if(result.status==200)
        {
            var r = result.data;

            $shop_class = $("#shop_class").combo({
                data:r,
                value: "id",
                text: "name",
                width: 110
            }).getCombo();
        }
    });

    THISPAGE.init();
    
});