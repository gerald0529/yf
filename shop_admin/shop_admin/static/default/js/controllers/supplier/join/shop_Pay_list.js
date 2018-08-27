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
            {name:'operating', label:'操作', width:70, fixed:true, formatter:operFmattershop, align:"center"},
            {name:'user_name', label:'店主账号',  width:200, align:"center"},
            {name:'shop_name', label:'店铺名称', width:200,align:'center',"formatter": handle.linkShopFormatter},
            {name:'shop_grade', label:'店铺等级',  width:100, align:"center"},
            {name:'shop_create_time', label:'开店时长',  width:150, align:"center"},
            {name:'shop_end_time', label:'到期时间',  width:150, align:"center"},
            {name:'shop_payment_cha', label:'付款状态',  width:100, align:"center"},
            {name:'shop_status_cha', label:'当前状态',  width:100, align:"center"},
            {name:'shop_class', label:'店铺分类',  width:100, align:"center"},
            {name:'contacts_name', label:'联系人名称',  width:100, align:"center"},
            {name:'contacts_phone', label:'联系人电话',  width:100, align:"center"},
            {name:'contacts_email', label:'联系人邮件',  width:150, align:"center"},
            {name:'shop_company_address', label:'所在区域',  width:150, align:"center"},
             {name:'company_address_detail', label:'详细地址',  width:300, align:"center"},
            {name:'company_employee_count', label:'员工总数',  width:100, align:"center"},
            {name:'company_registered_capital', label:'注册资金（万）',  width:100, align:"center"}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url: SITE_URL + "?ctl=Supplier_Manage&met=shopPay&typ=json",
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            height: Public.setGrid().h,
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
            forceFit:false,
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
//        if(row.shop_payment == 1){
//            var html_con = '<div class="operating" data-id="' + row.shop_id + '"><span class="ui-icon ui-icon-search" title="查看"></span><span class="ui-icon ui-icon-pencil" title="审核"></span></div>';
//        }else{
//            var html_con = '<div class="operating" data-id="' + row.shop_id + '"><span class="ui-icon ui-icon-search" title="查看"></span><span class="ui-icon ui-icon-pencil ui-icon-disabled" data-dis="1" title="审核"></span></div>';
//        }
        var html_con = '<div class="operating" data-id="' + row.shop_id + '"><span class="ui-icon ui-icon-search" title="审核"></span></div>';
        return html_con;
    };

    

    },
    reloadData: function(data){
        $("#grid").jqGrid('setGridParam',{postData: data}).trigger("reloadGrid");
    },
    addEvent: function(){
        var _self = this;
          //审核
//        $("#grid").on("click", ".operating .ui-icon-pencil", function (e)
//        {
//             if($(this).attr("data-dis")){
//                return 0;
//            }
//            e.preventDefault();
//            if (Business.verifyRight("INVLOCTION_DELETE"))
//            {
//                var e = $(this).parent().data("id");
//                handle.status(e)
//            }
//        });
        $('.grid-wrap').on('click', '.ui-icon-search', function(e){
            e.preventDefault();
            var shop_id = $(this).parent().data("id");
              $.dialog({
                title: "查看店铺详情",
                content: "url:"+ SITE_URL + '?ctl=Supplier_Manage&met=getShoplist&shop_id=' + shop_id,
                width: 1000,
                height: $(window).height(),
                max: !1,
                min: !1,
                cache: !1,
                lock: !0
            })
        
        });
        
        $('#search').click(function(){
            queryConditions.search_name = _self.$_searchName.val() === '请输入相关数据...' ? '' : _self.$_searchName.val();
            queryConditions.user_type = $source.getValue();
            queryConditions.shop_class = $shop_class.getValue();
            THISPAGE.reloadData(queryConditions);
        });
        //跳转到店铺认证信息页面
        $('#grid').on('click', '.to-shop', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var shop_id = $(this).attr('data-id');
            $.dialog({
                title: '查看店铺信息',
                content: "url:"+SITE_URL + '?ctl=Supplier_Manage&met=getShoplist&shop_id=' + shop_id,
                width: 1000,
                height: $(window).height(),
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
    linkShopFormatter: function(val, opt, row) {
        return '<a href="javascript:void(0)"><span class="to-shop" data-id="' + row.shop_id + '">' + val + '</span></a>';
    },
    operate: function (t, e)
    {
        f = 'complain-progress';
        parent.tab.addTabItem({
            tabid: f,
            text: '开店详情',
            url: SITE_URL + '?ctl=Supplier_Manage&met=getShoplist&shop_id=' + e
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
    },
    
    status: function (t)
    {
        $.dialog.confirm("审核通过店铺开启", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Supplier_Manage&met=editShopStatus&typ=json", {shop_id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "成功！"});
                    location.href= SITE_URL + "?ctl=Supplier_Manage&met=indexs"; 
                }
                else
                {
                    parent.Public.tips({type: 1, content: "失败！" + e.msg})
                }
            })
        })
    }
};
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

    THISPAGE.init();
    
});
