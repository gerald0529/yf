urlParam = Public.urlParam();
var queryConditions = {"otyp":urlParam.otyp},
		hiddenAmount = false, 
		SYSTEM = system = parent.SYSTEM;

if(urlParam.otyp==1 || urlParam.otyp==3){
	var cols = [
		{name:'operating', label:'操作', width:40, fixed:true, formatter:operFmatter, align:"center"},
		{name:'return_code', label:'退单编号', width:250, align:"center"},
		{name:'return_cash', label:'退单金额',  width:100, align:"center",sortable:true},
		{name:'return_commision_fee', label:'佣金金额',  width:100, align:"center",sortable:true},
		{name:'return_reason', label:'申请原因',  width:300, align:"center"},
		{name:'return_add_time', label:'申请时间',  width:150, align:"center",sortable:true},
		{name:'return_shop_message', label:'商家处理备注',  width:300, align:"center"},
		{name:'return_shop_time', label:'商家处理时间',  width:150, align:"center",sortable:true},
		{name:'order_number', label:'订单编号',  width:250, align:"center"},
		{name:'buyer_user_account', label:'买家',  width:150, align:"center"},
		{name:'seller_user_account', label:'商家',  width:150, align:"center"}
	];
}else if(urlParam.otyp==2){
	var cols = [
		{name:'operating', label:'操作', width:40, fixed:true, formatter:operFmatter, align:"center"},
		{name:'return_code', label:'退单编号', width:200, align:"center"},
		{name:'return_cash', label:'退单金额',  width:100, align:"center",sortable:true},
		{name:'return_commision_fee', label:'佣金金额',  width:100, align:"center",sortable:true},
		{name:'return_reason', label:'申请原因',  width:300, align:"center"},
		{name:'return_add_time', label:'申请时间',  width:150, align:"center",sortable:true},
		{name:'order_goods_name', label:'涉及商品',  width:400, align:"left"},
		{name:'return_shop_message', label:'商家处理备注',  width:300, align:"center"},
		{name:'return_shop_time', label:'商家处理时间',  width:150, align:"center",sortable:true},
		{name:'order_number', label:'订单编号',  width:200, align:"center"},
		{name:'buyer_user_account', label:'买家',  width:150, align:"center"},
		{name:'seller_user_account', label:'商家',  width:150, align:"center"}
	];
}


//操作符
function operFmatter (val, opt, row) {
	var html_con = '<div class="operating" data-id="' + row.id + '"><a data-right="BU_QUERY" parentOpen="true" href="'+SITE_URL+'?ctl=Trade_Return&met=detail&id='+row.id+'" rel="pageTab" tabid="return-detail" tabtxt="处理退款"><span class="ui-icon ui-icon-pencil" title="处理"></span></a></div>';
	return html_con;
};
	var THISPAGE = {
		init: function(data){
			if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
				hiddenAmount = true;
			};
			this.mod_PageConfig = Public.mod_PageConfig.init('return-all-list');//页面配置初始化
			this.initDom();
			this.loadGrid();            
			this.addEvent();
		},
		
		initDom: function(){
			this.$_searchName = $('#searchName');
		},
		
		loadGrid: function(){
			var gridWH = Public.setGrid(), _self = this;
			
			var colModel = cols;
			
			this.mod_PageConfig.gridReg('grid', colModel);
			colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
			
			$("#grid").jqGrid({
				url:SITE_URL +  '?ctl=Trade_Return&met=getReturnAllList&typ=json',
				postData: queryConditions,
				datatype: "json",
				autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
				height: Public.setGrid().h,
				altRows: true, //设置隔行显示
				gridview: true,
				multiselect: true,
				multiboxonly: true,
				colModel:colModel,
				cmTemplate: {sortable: false, title: false},
				page: 1, 
				sortname: 'number',    
				sortorder: "desc", 
				pager: "#page",  
				rowNum: 100,
				rowList:[10,20,50],
				viewrecords: true,
				shrinkToFit: false,
				forceFit: true,
				jsonReader: {
				  root: "data.items", 
				  records: "data.records",  
				  repeatitems : false,
				  total : "data.total",
				  id: "order_return_id"
				},
				onSelectRow: function(rowid){
					var b = $('#grid').jqGrid('getGridParam', 'selarrrow'),
						c = b.join();
					Public.ajaxPost('./index.php?ctl=Trade_Return&met=CountAmount&typ=json', {
						id: c
					}, function (a) {
						$('.count_amount').html(a.data.money);
					})
				},
				onSelectAll:function(rowids,statue){
					var b = $('#grid').jqGrid('getGridParam', 'selarrrow'),
						c = b.join();
					Public.ajaxPost('./index.php?ctl=Trade_Return&met=CountAmount&typ=json', {
						id: c
					}, function (a) {
						$('.count_amount').html(a.data.money);
					})
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

		},
		
		//重新加载数据
		reloadData: function(data){
			$("#grid").jqGrid('setGridParam',{postData: data}).trigger("reloadGrid");
		},
		
		//增加事件
		addEvent: function()
		{
			var _self = this;

			$('#search').click(function(){

				queryConditions.return_code = $('#return_code').val();
				queryConditions.seller_user_account = $('#seller_user_account').val();
				queryConditions.buyer_user_account = $('#buyer_user_account').val();
				queryConditions.order_goods_name = $('#order_goods_name').val();
				queryConditions.order_number = $('#order_number').val();
				queryConditions.start_time = $('#start_time').val();
				queryConditions.end_time = $('#end_time').val();
				queryConditions.min_cash = $('#min_cash').val();
				queryConditions.max_cash = $('#max_cash').val();
				THISPAGE.reloadData(queryConditions);
			});

			$("#btn-excel").click(function ()
			{
				var query = "";
				for (x in queryConditions)
				{
					query = query + "&" + x + "=" + queryConditions[x];
				}
				window.open(SHOP_URL + "?ctl=Api_Trade_Export&met=getReturnAllExcel&debug=1"+query);
			});
			//刷新
			$("#btn-refresh").click(function ()
			{
				queryConditions.return_code = '';
				queryConditions.seller_user_account ='';
				queryConditions.buyer_user_account ='';
				queryConditions.order_goods_name ='';
				queryConditions.order_number ='';
				queryConditions.start_time ='';
				queryConditions.end_time ='';
				queryConditions.min_cash = '';
				queryConditions.max_cash ='';
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
		Public.pageTab();
		THISPAGE.init(); 
	});