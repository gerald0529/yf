var queryConditions = {},  
    hiddenAmount = false, 
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('contract-type-list');//页面配置初始化
        this.initDom();
        this.loadGrid();            
        this.addEvent();
    },
    initDom: function(){
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        var colModel = [
		{
		    name: "operating",
		    label:'操作',
		    width:40,
		    fixed: !0,
		    align: "center",
		    formatter: operFmatter
		}, 
		{name: "contract_type_sort", label: "排序", align: "center",width: 100,sortable:true},
		{name: "contract_type_name", label: "项目名称", align: "center",width: 200,sortable:false},
		{name: "contract_type_cash", label: "保证金(元)", align: "center",width: 100,sortable:false},
		{name: "contract_type_state_text", label: "状态", align: "center",width: 100,sortable:false},
		{name: "contract_type_logo", hidden:true},
		{name: "contract_type_desc", hidden:true},
		{name: "contract_type_url", hidden:true},
		{name: "contract_type_state", hidden:true},
		{name: "contract_type_id", hidden:true}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL + "?ctl=Operation_Contract&met=getTypeList&typ=json",
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
            var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span></div>';
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

	//编辑
        $('#grid').on('click', '.ui-icon-pencil', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.operate("edit", e)
        });
	
        $("#btn-refresh").click(function ()
        {
            queryConditions.grid ='';
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
	var i = "修改保障服务", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
        $.dialog({
            title: i,
            content: "url:"+SITE_URL+"?ctl=Operation_Contract&met=manage",
            data: a,
            width: 650,
            height:$(window).height(),
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
    }
};
$(function(){

    Public.pageTab();
    
    THISPAGE.init();
    
});