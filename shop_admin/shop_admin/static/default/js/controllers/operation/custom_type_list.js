var queryConditions = {},  
    hiddenAmount = false, 
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('custom-type-list');//页面配置初始化
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
		{name: "custom_service_type_id", hidden:true, align: "center",width: 80,sortable:true},
		{name: "custom_service_type_sort", label: "排序", align: "center",width:100,sortable:true},
		{name: "custom_service_type_name", label: "咨询类型名称", align: "center",width: 200,sortable:false},
		{name: "custom_service_type_desc", label: "咨询类型备注", align: "center",width: 300,sortable:false,align:"left"}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL + "?ctl=Operation_Custom&met=getTypeList&typ=json",
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
              id: "custom_service_type_id"
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
            var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
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
	//添加
	$("#btn-add").click(function (t)
	{
	    t.preventDefault();
	    Business.verifyRight("INVLOCTION_ADD") && handle.operate("add")
	});
    
	//编辑
        $('#grid').on('click', '.ui-icon-pencil', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.operate("edit", e)
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
	
        $('#search').click(function(){
            queryConditions.search_name = _self.$_searchName.val() === '请输入相关数据...' ? '' : _self.$_searchName.val();
            queryConditions.user_type = $source.getValue();
            THISPAGE.reloadData(queryConditions);
        });

        $("#btn-refresh").click(function ()
        {
            queryConditions.search_name='';
            queryConditions.user_type='';
            THISPAGE.reloadData(queryConditions);
            _self.$_searchName.val('请输入相关数据...');
        });

        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};
var handle = {
    operate: function (t, e)
    {
        if ("add" == t)
        {
            var i = "新增咨询类型", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "修改咨询类型", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
            console.info(a);
        }
        $.dialog({
            title: i,
            content: "url:"+SITE_URL+"?ctl=Operation_Custom&met=manage",
            data: a,
            width: 650,
            height: 300,
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
        else
        {
            $("#grid").jqGrid("addRowData", t.member_id, t, "last");
            i && i.api.close();
	    $("#grid").trigger("reloadGrid");
        }
    }, del: function (t)
    {
        $.dialog.confirm("删除的类型将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL+"?ctl=Operation_Custom&met=delType&typ=json", {id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "咨询类型删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "咨询类型删除失败！" + e.msg})
                }
            })
        })
    }
};
$(function(){

    Public.pageTab();
    
    THISPAGE.init();
    
});