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
        this.mod_PageConfig = Public.mod_PageConfig.init('card_info_list');//页面配置初始化
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
		{name: "card_code", label: "卡号", align: "center",width: 150,sortable:false},
		{name: "card_password", label: "卡片密码", align: "center",width: 100,sortable:false},
		{name: "card_fetch_time", label: "领奖时间", align: "center",width: 150,sortable:false},
		{name: "user_account", label: "领卡人账号", align: "center",width: 150,sortable:false},
		{name: "user_id", label: "领卡人ID", align: "center",width: 80,sortable:false},
		{name: "card_time", label: "生成时间", align: "center",width: 150,sortable:false}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL + "?ctl=Operation_Card&met=getCardInfoList&typ=json",
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
              total : "data.total"
            },
            loadError : function(xhr,st,err) {
                
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
            var html_con = '<div class="operating" data-id="' + row.card_id + '"><a data-right="BU_QUERY" parentOpen="true" href="'+SITE_URL+'?ctl=Operation_Card&met=getDetail&id='+row.card_id+'" rel="pageTab" tabid="contract-detail" tabtxt="查看消费卡详情"><span class="ui-icon ui-icon-gear" title="查看详情"></span></a>';
			if(row.card_used_num==0){
				html_con += '<span class="ui-icon ui-icon-trash" title="删除"></span>';
			}
			html_con += '</div>';
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
	var i = "新增充值卡", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
        $.dialog({
            title: i,
            content: "url:"+SITE_URL+"?ctl=Operation_Card&met=addCard",
            data: a,
            width: 600,
            height: 450,
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
        if ("add" == e)
        {
            i && i.api.close();
	    $("#grid").trigger("reloadGrid");
        }
    }, del: function (t)
    {
        $.dialog.confirm("删除的充值卡将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL+"?ctl=Operation_Card&met=delCard&typ=json", {id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "咨询类型删除成功！"});
                    $("#grid").trigger("reloadGrid");
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





