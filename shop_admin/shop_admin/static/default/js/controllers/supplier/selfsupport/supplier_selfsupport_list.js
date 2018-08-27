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
            {name:'operating', label:'操作', width:90, fixed:true, formatter:operFmattershop, align:"center"},
            {name:'user_name', label:'店主账号',  width:200, align:"center"},
            {name:'shop_name', label:'店铺名称', width:200,align:'center'},
            {name:'shop_status_cha', label:'当前状态',  width:100, align:"center"},
            {name:'shop_all_class_cha', label:'是否绑定所有类目',  width:100, align:"center"}
     
               
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL +  "?ctl=Supplier_Selfsupport&met=shopIndex&typ=json",
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
        
    
function operFmattershop(val, opt, row) {
    if(row.shop_all_class){
        var html_con = '<div class="operating" data-id="' + row.shop_id + '"><span class="ui-icon ui-icon-gear" title="修改"></span><span class="ui-icon ui-icon-pencil ui-icon-disabled" data-dis="1" title="修改经营类目"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
    }else{
        var html_con = '<div class="operating" data-id="' + row.shop_id + '"><span class="ui-icon ui-icon-gear" title="修改"></span><span class="ui-icon ui-icon-pencil  title="修改经营类目"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';

    }
    return html_con;
};

    

    },
    reloadData: function(data){
        $("#grid").jqGrid('setGridParam',{postData: data}).trigger("reloadGrid");
    },
    addEvent: function(){
        var _self = this;
       $("#btn-add").click(function (t)
    {
        t.preventDefault();
        Business.verifyRight("INVLOCTION_ADD") && handle.operate("Add")
    });
        //编辑
        $('.grid-wrap').on('click', '.ui-icon-gear', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.operate("Edit", e)
        });
        	//编辑
	$('.grid-wrap').on('click', '.ui-icon-pencil', function(e){
            if($(this).attr("data-dis")){
                return 0;
            }
            e.preventDefault();
            var shop_id = $(this).parent().data("id");
            parent.tab.addTabItem({
            tabid: shop_id,
            text: '经营类目',
            url: SITE_URL + '?ctl=Shop_Manage&met=editCategory&shop_id=' + shop_id,
            })
        });
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
            THISPAGE.reloadData('');
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
         if ("Add" == t)
        {
            var i = "新增店铺", a = {oper: t, callback:testF};
        }
        else
        {
            var i = "修改店铺", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback:testF};
           
        }
        $.dialog({
            title: i,
            content: "url:"+SITE_URL+"?ctl=Supplier_Selfsupport&met=getShop"+ t +"Row&shop_id="+e, 
            data: a,
            width: 600,
            height: 250,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
            })
    },del: function (t)
    {
        $.dialog.confirm("删除的店铺将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Supplier_Selfsupport&met=delSelfsupport&typ=json", {shop_id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "店铺删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "店铺删除失败！" + e.msg})
                }
            })
        })
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

    THISPAGE.init();
    
});
