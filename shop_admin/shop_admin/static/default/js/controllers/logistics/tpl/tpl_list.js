var queryConditions = {
       
    },  
    hiddenAmount = false, 
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('user_list');//页面配置初始化
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
            {name:'waybill_tpl_name', label:'模板名称', width:200, align:"center"},
         
            {name:'express_name', label:'快递公司', width:100,align:'center'},
            {name:'waybill_tpl_image', label:'运单图例', width:200,align:'center',"formatter": handle.imgFmt ,classes:'img_flied'},
            {name:'waybill_tpl_width', label:'宽度(mm)', width:100, align:"center"},
            {name:'waybill_tpl_height', label:'高度(mm)', width:100, align:"center"},
            {name:'waybill_tpl_enable', label:'是否启用', width:100, align:"center"},
            {name:'waybill_tpl_top', label:'上偏移', width:100, align:"center"},
            {name:'waybill_tpl_left', label:'左偏移', width:100, align:"center"}
            
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL +  '?ctl=Logistics_Waybill&met=getWaybillTplList&typ=json',
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
            rowList:[100,200,500], 
            viewrecords: true,
            shrinkToFit: false,
            forceFit: true,
            jsonReader: {
              root: "data.items", 
              records: "data.records",  
              repeatitems : false,
              total : "data.total",
              id: "waybill_tpl_id"
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
            var html_con = '<div class="operating" data-id="' + row.id+ '"><span class="ui-icon ui-icon-pencil" title="编辑"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div></div>';
            return html_con;
        };

        
    },
    reloadData: function(data){
        $("#grid").jqGrid('setGridParam',{postData: data}).trigger("reloadGrid");
    },
    addEvent: function(){
        var _self = this;
		//添加
		$("#btn-add").click(function (t)
		{
			t.preventDefault();
			Business.verifyRight("INVLOCTION_ADD") && handle.add("id")
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
		//查询
		 $('#search').click(function(){
          
			queryConditions.waybill_tpl_name = $('#waybill_tpl_name').val();
            THISPAGE.reloadData(queryConditions);
        });
        //编辑
        $('.grid-wrap').on('click', '.ui-icon-pencil', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");

            handle.operate("edit", e)
        });
       $("#btn-refresh").click(function ()
        {
            THISPAGE.reloadData('');
            _self.$_searchName.placeholder('请输入模板名称...');
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
		var i = "编辑运单模板信息", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
        $.dialog({
            title: i,
            content: "url:"+SITE_URL + '?ctl=Logistics_Waybill&met=editWaybillTpl&id=' + e,
            data: a,
            width: 600,
            // height: $(window).height()*0.7,
            height:580,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
       
    },add: function (t, e)
    {
		var i = "增加运单模板信息", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
        $.dialog({
            title: i,
            content: "url:"+SITE_URL+"?ctl=Logistics_Waybill&met=addWaybillTpl",
            data: a,
            width: 600,
            // height: $(window).height()*0.7,
            height:580,
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
        a[t.id] = t;
        i && i.api.close();
		$("#grid").trigger("reloadGrid");
		
    },del: function (t)
    {
        $.dialog.confirm("删除的模板将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL+"?ctl=Logistics_Waybill&met=delWaybillTpl&typ=json", {id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "运单模板删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "运单模板删除失败！" + e.msg})
                }
            })
        })
    },imgFmt: function (val, opt, row)
    {
        if (row.level == 0 && val)
        {
            val = '<img src="' + val + '">';
        }
        else
        {
            if (row.waybill_tpl_image)
            {
                val = '<img height="30" width="100" src="' + row.waybill_tpl_image + '">';
            }
            else
            {
                val = '&#160;';
            }
        }
        return val;
    }
	
       
};
$(function(){
   Public.pageTab();
    THISPAGE.init();
    
});
