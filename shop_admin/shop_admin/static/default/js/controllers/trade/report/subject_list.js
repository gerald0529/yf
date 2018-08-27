	var queryConditions = {},  
		hiddenAmount = false, 
		SYSTEM = system = parent.SYSTEM;
	
	var THISPAGE = {
		init: function(data){
			if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
				hiddenAmount = true;
			};
			this.mod_PageConfig = Public.mod_PageConfig.init('report-subject-list');//页面配置初始化
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
				{name:'operating', label:'操作', fixed:true, width:60,formatter:operFmatter, align:"center"},
				{name:'report_type_name', label:'举报类型',  width:150, align:"center"},
				{name:'report_subject_name', label:'举报主题', width:350, align:"center"}
			];
			
			this.mod_PageConfig.gridReg('grid', colModel);
			colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
			
			$("#grid").jqGrid({
				url:SITE_URL +  '?ctl=Trade_Report&met=getSubjectList&typ=json',
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
				var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
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
			//添加
			$("#btn-add").click(function (t)
			{
				t.preventDefault();
				Business.verifyRight("INVLOCTION_ADD") && handle.operate("add")
			});
			//添加
			$('#grid').on('click', '.ui-icon-pencil', function(e){
				e.preventDefault();
				var e = $(this).parent().data("id");
				handle.editop("edit", e)
			});

			//删除
			$("#grid").on("click", ".operating .ui-icon-trash", function (t)
			{
				t.preventDefault();
				if (Business.verifyRight("INVLOCTION_DELETE"))
				{
					var e = $(this).parent().data("id");
					handle.del(e)
				}
			});

			
			//刷新
			$("#btn-refresh").click(function ()
			{
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
			var i = "新增主题", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
			$.dialog({
				title: i,
				content: "url:"+SITE_URL+"?ctl=Trade_Report&met=addSubject",
				data: a,
				width: 500,
				height: 170,
				max: !1,
				min: !1,
				cache: !1,
				lock: !0
			})
		}, editop: function (t, e)
		{
			var i = "编辑主题", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
			$.dialog({
				title: i,
				content: "url:"+SITE_URL+"?ctl=Trade_Report&met=editSubject&id="+e,
				data: a,
				width: 500,
				height: 170,
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
		}, del: function (t)
		{
			$.dialog.confirm("删除的主题将不能恢复，请确认是否删除？", function ()
			{
				Public.ajaxPost(SITE_URL+"?ctl=Trade_Report&met=delSubject&typ=json", {id: t}, function (e)
				{
					if (e && 200 == e.status)
					{
						parent.Public.tips({content: "主题删除成功！"});
						$("#grid").trigger("reloadGrid");
					}
					else
					{
						parent.Public.tips({type: 1, content: "主题删除失败！" + e.msg})
					}
				})
			})
		}
	};
	
	$(function(){
		THISPAGE.init(); 
	});