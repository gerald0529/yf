if ( window.location.href.indexOf('pid') > -1 ) 
{ 
	var apply_string = window.location.href.match(/pid=\d+/); 
	if ( apply_string && apply_string[0]) 
	{ 
	var pid = apply_string[0].match(/\d+/); 
	} 
}
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
            {name:'user_name', label:'店主账号',  width:100, align:"center"},
            {name:'shop_name', label:'店铺名称', width:150,align:'center'},
            {name:'shop_logo', label:'店铺logo', width:100,align:"center", "formatter": handle.imgFmt ,classes:'img_flied'},
            {name:'shop_create_time', label:'开店时间',  width:150, align:"center"},
            {name:'shop_end_time', label:'到期时间',  width:150, align:"center"},
            {name:'shop_company_address', label:'所在区域',  width:120, align:"center"},
            {name:'shop_qq', label:'qq',  width:100, align:"center"},
            {name:'shop_ww', label:'旺旺',  width:100, align:"center"},
            {name:'shop_tel', label:'商家电话',  width:100, align:"center"}

               
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL +  "?ctl=Supplier_Manage&met=distributor_list&pid="+pid+"&typ=json",
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
            forceFit: false,
            jsonReader: {
              root: "data.items",
              records: "data.records",
              repeatitems : false,
              total : "data.total",
              id: "shop_id"
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
        
    function operFmattershop(val, opt, row) {
	    var html_con = '<div class="operating" data-id="' + row.shop_id + '"><span class="ui-icon ui-icon-search shopinfo" title="查看"></span><span class="ui-icon ui-icon-search distgoods" title="分销商品"></span><span class="ui-icon ui-icon-search orderlist" title="分销业绩"></span></div>';
	    return html_con;
	};

    

    },
    reloadData: function(data){
        $("#grid").jqGrid('setGridParam',{postData: data}).trigger("reloadGrid");
    },
    addEvent: function(){
        var _self = this;

        $('#search').click(function(){
            queryConditions.search_name = _self.$_searchName.val() === '请输入相关数据...' ? '' : _self.$_searchName.val();
            queryConditions.user_type = $source.getValue();
            queryConditions.shop_class = $shop_class.getValue();
            THISPAGE.reloadData(queryConditions);
        });
        
		//分销业绩
		$('.grid-wrap').on('click', '.orderlist', function(e){
            e.stopPropagation();
            e.preventDefault();
            var shop_id = $(this).parent().data("id");
            $.dialog({
                title: '分销业绩',
                content: "url:"+SITE_URL + '?ctl=Supplier_Manage&met=distributor_order&shop_id=' + shop_id,
                width: 950,
                height: $(window).height() * 0.9,
                max: !1,
                min: !1,
                cache: !1,
                lock: !0
            })
        });
        
        //分销商品
        $('#grid').on('click', '.distgoods', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var shop_id = $(this).parent().data("id");
            $.dialog({
                title: '分销商品',
                content: "url:"+SITE_URL + '?ctl=Supplier_Manage&met=distributor_goods&shop_id=' + shop_id,
                width: 950,
                height: $(window).height() * 0.9,
                max: !1,
                min: !1,
                cache: !1,
                lock: !0
            })
        });

        //查看店铺详情
		$('.grid-wrap').on('click', '.shopinfo', function(e){
            e.preventDefault();
            var shop_id = $(this).parent().data("id");
              $.dialog({
                title: "查看店铺详情",
                content: "url:"+ SITE_URL + '?ctl=Supplier_Manage&met=getShoplist&shop_id=' + shop_id,
                width: 950,
                height: $(window).height() * 0.9,
                max: !1,
                min: !1,
                cache: !1,
                lock: !0
            })
        
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
        if ("add" == t)
        {
            var i = "新增店铺", a = {oper: t,  callback:testF};
        }
        else
        {
            var i = "编辑店铺", a = {oper: t, callback:testF};
           
        }
        $.dialog({
            title: i,
            content: "url:"+ SITE_URL + "?ctl=Supplier_Manage&met=getinformationrow&shop_id=" + e,
            data: a,
            width: 700,
            height: 500,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
      
    
  
    }, 

      imgFmt: function (val, opt, row)
    {
        if (row.level == 0 && val)
        {
            val = '<img src="' + val + '">';
        }
        else
        {
            if (row.shop_logo)
            {
                val = '<img src="' + row.shop_logo + '">';
            }
            else
            {
                val = '&#160;';
            }
        }
        return val;
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

    $.get("./index.php?ctl=Shop_Class&met=shopClass&typ=json", function(result){
        if(result.status==200)
        {
            var r = result.data;

            $shop_class = $("#shop_class").combo({
                data:r,
                value: "id",
                text: "name",
                width: 110
            }).getCombo();
        }
    });

    /*$shop_class = $("#shop_class").combo({
        data: [{
            id: "0",
            name: "分类111"
        },{
            id: "1",
            name: "分类222"
        }],
        value: "id",
        text: "name",
        width: 110
    }).getCombo();*/

    THISPAGE.init();
    
});
