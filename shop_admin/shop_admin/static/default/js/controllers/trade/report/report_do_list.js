urlParam = Public.urlParam();
var queryConditions = {"otyp":urlParam.otyp},
		hiddenAmount = false, 
		SYSTEM = system = parent.SYSTEM;
	
	var THISPAGE = {
		init: function(data){
			if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
				hiddenAmount = true;
			};
			this.mod_PageConfig = Public.mod_PageConfig.init('report-do-list');//页面配置初始化
			this.initDom();
			this.loadGrid();            
			this.addEvent();
		},
		
		initDom: function(){
			this.$_searchName = $('#searchName');
		},
		
		loadGrid: function(){
			var gridWH = Public.setGrid(), _self = this;
			
			var colModel = [
				{name:'operating', label:'操作', width:60, fixed:true, formatter:operFmatter, align:"center"},
				{name:'user_account', label:'举报人', width:100, align:"center"},
				{name:'report_type_name', label:'举报类型',  width:100, align:"center"},
				{name:'report_subject_name', label:'举报主题',  width:200, align:"center"},
				{name:'goods_name', label:'举报商品',  width:600, align:"left"},
				{name:'report_date', label:'举报时间',  width:140, align:"center",sortable:true},
				{name:'shop_name', label:'涉及商家',  width:150, align:"center"}
			];
			
			this.mod_PageConfig.gridReg('grid', colModel);
			colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
			
			$("#grid").jqGrid({
				url:SITE_URL +  '?ctl=Trade_Report&met=getReportList&typ=json',
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
				  id: "card_id"
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
			
			//操作符
			function operFmatter (val, opt, row) {
				var html_con = '<div class="operating" data-id="' + row.id + '"><a data-right="BU_QUERY" parentOpen="true" href="'+SITE_URL+'?ctl=Trade_Report&met=look&id='+row.id+'" rel="pageTab" tabid="report-look" tabtxt="查看详情"><span class="ui-icon ui-icon-search" title="查看详情"></span></a><span class="ui-icon ui-icon-pencil" title="处理"></span></div>';
				return html_con;
			};
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

				queryConditions.goods_name = $('#goods_name').val();
				queryConditions.shop_name = $('#shop_name').val();
				queryConditions.user_account = $('#user_account').val();
				queryConditions.report_subject_name = $('#report_subject_name').val();
				queryConditions.report_type_name = $('#report_type_name').val();
				THISPAGE.reloadData(queryConditions);
			});

			//添加
			$('#grid').on('click', '.ui-icon-pencil', function(e){
				e.preventDefault();
				var e = $(this).parent().data("id");
				handle.editop("edit", e)
			});
			//刷新
			$("#btn-refresh").click(function ()
			{
				queryConditions.goods_name = '';
				queryConditions.shop_name = '';
				queryConditions.user_account = '';
				queryConditions.report_subject_name = '';
				queryConditions.report_type_name = '';
				THISPAGE.reloadData(queryConditions);
			});

			$(window).resize(function(){
				Public.resizeGrid();
			});
		}
	};

var handle = {
	editop: function (t, e)
	{
		var i = "处理投诉", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
		$.dialog({
			title: i,
			content: "url:"+SITE_URL+"?ctl=Trade_Report&met=detail&id="+e,
			data: a,
			width: 500,
			height: 222,
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
		i && i.api.close();
		$("#grid").trigger("reloadGrid");
	}
};

	$(function(){
		Public.pageTab();
		THISPAGE.init(); 
	});