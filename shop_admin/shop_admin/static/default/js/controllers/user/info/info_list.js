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
		this.$_searchName.placeholder();
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        var colModel = [
            {name:'user_id', label:'会员id', width:100, align:"center"},
         
            {name:'user_name', label:'会员名称', width:200,align:'center'},
            {name:'user_email', label:'会员邮箱', width:200,align:'center'},
            {name:'user_mobile', label:'会员手机', width:100, align:"center"},
            {name:'user_sex', label:'会员性别', width:100, align:"center"},
            {name:'user_realname', label:'真实姓名', width:100, align:"center"},
            {name:'user_birthday', label:'出生日期', width:150, align:"center","formatter":timeType},
            {name:'user_regtime', label:'注册时间', width:150, align:"center","formatter":timeType},
            {name:'shop_type', label:'商家类型', width:100, align:"center","formatter":shop_type},
            {name:'lastlogintime', label:'最后登录时间', width:150, align:"center","formatter":timeType}
           
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL +  '?ctl=User_Info&met=getInfoList&typ=json',
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
              id: "user_id"
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
        
        function shop_type(val, opt, row){
        	var str = '';
        	if(row.shop_type == 1 && row.shop_status == 3){
        		str = '商家店铺';
        	}else if(row.shop_type == 2 && row.shop_status == 3){
        		str = '供货商店铺';
        	}else{
                str = '无';
            }
        	return str;
        }

        function timeType(val, opt, row)
        {
            var str = '';
            var myDate = new Date(val);

            if(!isNaN(myDate.getTime()))
            {
                str = val;
            }
            return str;
        }
    
        function operFmatter (val, opt, row) {
            var html_con = '<div class="operating" data-id="' + row.id+ '"><span class="ui-icon ui-icon-pencil" title="编辑"></span></div>';
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
			Business.verifyRight("INVLOCTION_ADD") && handle.add("id")
		});
		//查询
		 $('#search').click(function(){
            
            queryConditions.search_name = _self.$_searchName.val() === '请输入相关数据...' ? '' : _self.$_searchName.val();
            queryConditions.user_type = $source.getValue();
            queryConditions.shop_source = $shop_source.getValue();
            THISPAGE.reloadData(queryConditions);
        });
        //编辑
        $('.grid-wrap').on('click', '.ui-icon-pencil', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");

            handle.operate("edit", e)
        });
		//导出
		$("#btn-excel").click(function ()
        {
            var query = "";
            for (x in queryConditions)
            {
                query = query + "&" + x + "=" + queryConditions[x];
            }
            window.open(SHOP_URL + "?ctl=Api_User_Info&met=getInfoExcel&debug=1"+query);
        });
       $("#btn-refresh").click(function ()
        {
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
		var i = "编辑会员信息", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
        $.dialog({
            title: i,
            content: "url:"+SITE_URL + '?ctl=User_Info&met=editInfo&user_id=' + e,
            data: a,
            width: 600,
            height: $(window).height()*0.9,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
       
    },add: function (t, e)
    {
		var i = "增加会员信息", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
        $.dialog({
            title: i,
            content: "url:"+SITE_URL+'?ctl=User_Info&met=addInfo',
            data: a,
            width: 600,
            height: $(window).height()*0.9,
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
		
    }
 
};
$(function(){
  $source = $("#source").combo({
        data: [{
            id: "1",
            name: "会员id"
        },{
            id: "2",
            name: "会员名称"
        }],
        value: "id",
        text: "name",
        width: 110
    }).getCombo();

	$shop_source = $("#shop_source").combo({
		data:[{
			id:"0",
			name:"商家类型"
		},
		{
			id:"1",
			name:"商家店铺"
		},{
			id:"2",
			name:"供货商店铺"
		}],
		value:"id",
		text:"name",
		width:120
	}).getCombo();
	
	
    Public.pageTab();

    THISPAGE.init();
    
});
