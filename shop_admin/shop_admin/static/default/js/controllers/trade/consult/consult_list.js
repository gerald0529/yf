	var queryConditions = {},  
		hiddenAmount = false, 
		SYSTEM = system = parent.SYSTEM;
	
	var THISPAGE = {
		init: function(data){
			if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
				hiddenAmount = true;
			};
			this.mod_PageConfig = Public.mod_PageConfig.init('consult-list');//页面配置初始化
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
				{name:'operating', label:'操作', width:40, fixed:true, formatter:operFmatter, align:"center"},
				{name:'user_account', label:'咨询人', width:140, align:"center"},
				{name:'consult_question', label:'咨询内容',  width:477, align:"left"},
				{name:'goods_name', label:'咨询商品',  width:560, align:"left"},
				{name:'question_time', label:'咨询时间',  width:155, align:"center",sortable:true},
				{name:'shop_name', label:'商家名称',  width:200, align:"center"}
			];
			
			this.mod_PageConfig.gridReg('grid', colModel);
			colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
			
			$("#grid").jqGrid({
				url:SITE_URL +  '?ctl=Trade_Consult&met=getConsultList&typ=json',
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
				var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
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

			$('#search').click(function(){
				queryConditions.consult_question = $('#consult_question').val();
				queryConditions.user_account = $('#user_account').val();
				queryConditions.start_time = $('#start_time').val();
				queryConditions.end_time = $('#end_time').val();
				THISPAGE.reloadData(queryConditions);
			});

			//刷新
			$("#btn-refresh").click(function ()
			{
				queryConditions.consult_question ='';
				queryConditions.user_account ='';
				queryConditions.start_time ='';
				queryConditions.end_time ='';
				THISPAGE.reloadData(queryConditions);
			});

			$(window).resize(function(){
				Public.resizeGrid();
			});
		}
	};
	
	var handle = {
		del: function (t)
		{
			$.dialog.confirm("删除的理由将不能恢复，请确认是否删除？", function ()
			{
				Public.ajaxPost(SITE_URL+"?ctl=Trade_Consult&met=delConsult&typ=json", {id: t}, function (e)
				{
					if (e && 200 == e.status)
					{
						parent.Public.tips({content: "理由删除成功！"});
						$("#grid").trigger("reloadGrid");
					}
					else
					{
						parent.Public.tips({type: 1, content: "理由删除失败！" + e.msg})
					}
				})
			})
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
		THISPAGE.init(); 
	});