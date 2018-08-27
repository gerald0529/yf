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
            {name:'shop_name', label:'店铺名称', width:200,align:'center',"formatter": handle.linkShopFormatter},
            {name:'user_name', label:'店主账号',  width:200, align:"center"},
            {name:'commission_rate', label:'分佣比例（%）',  width:100, align:"center"},
            {name:'shop_class_bind_enablecha', label:'状态',  width:100, align:"center"},
            {name:'cat_namenum', label:'经营类目',  width:400, align:"left"},
            {name:'shop_class_bind_desc', label:'拒绝理由',  width:300, align:"left"}

               
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL +  "?ctl=Supplier_Manage&met=getCategory&typ=json",
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
            forceFit: true,
            jsonReader: {
              root: "data.items",
              records: "data.records",
              repeatitems : false,
              total : "data.total",
              id: "shop_class_bind_id"
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
          if(row.shop_class_bind_enable==1){
            var html_con = '<div class="operating" data-id="' + row.shop_class_bind_id + '"><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-pencil" title="审核"></span></div>';
            }else{
            var html_con = '<div class="operating" data-id="' + row.shop_class_bind_id + '"><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-pencil ui-icon-disabled" data-dis="1" title="审核"></span></div>';
            }
     
    return html_con;
};

    

    },
    reloadData: function(data){
        $("#grid").jqGrid('setGridParam',{postData: data}).trigger("reloadGrid");
    },
    addEvent: function(){
        var _self = this;
        //删除
             $("#grid").on("click", ".operating .ui-icon-trash", function (e)
        {
           
            e.preventDefault();
            if (Business.verifyRight("INVLOCTION_DELETE"))
            {
                var e = $(this).parent().data("id");
                handle.del(e)
            }
        });
        
                //审核
        $("#grid").on("click", ".operating .ui-icon-pencil", function (e)
        {
          if($(this).attr("data-dis")){
                return 0;
            }
            e.preventDefault();
            if (Business.verifyRight("INVLOCTION_DELETE"))
            {
                var e = $(this).parent().data("id");
                handle.status(e)
            }
        });
        $('#search').click(function(){
            queryConditions.search_name = _self.$_searchName.val() === '请输入相关数据...' ? '' : _self.$_searchName.val();
            queryConditions.user_type = $source.getValue();
            queryConditions.status = $shop_class_bind_enable.getValue();
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
                height:$(window).height(),
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
     del: function (t)
    {
        $.dialog.confirm("该类目已经审核通过，删除它可能影响到商家的使用，确认删除吗？", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Supplier_Manage&met=delCategory&typ=json", {shop_class_bind_id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "类目删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "类目删除失败！" + e.msg})
                }
            })
        })
    },
        status: function (t)
    {
        $.dialog({
            id: 'audit',
            title: '审核经营类目',
            content: "<div><input style='width: 300px;' class='ui-input ui-input-ph con' type='text' id='refusalReason' placeholder='如果拒绝，请填写拒绝理由！' /></div>",
            width: 350,
            okVal: '通过',

            ok: function () {
                auditShopCategory(1)
            },
            button: [{id:'fail', name:'拒绝', focus:1, callback: function () {
                var refusalReason = this.DOM.wrap.find("#refusalReason").val();
                auditShopCategory(0, refusalReason);
            }}]
        });

        function auditShopCategory (pass, refusalReason) {
            Public.ajaxPost(SITE_URL + "?ctl=Supplier_Manage&met=categoryStatus&typ=json", {shop_class_bind_id: t, pass: pass, refusalReason: refusalReason}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "成功！"});
                    location.href= SITE_URL + "?ctl=Supplier_Manage&met=category";
                }
                else
                {
                    parent.Public.tips({type: 1, content: "失败！" + e.msg})
                }
            })
        }
    }
};
$(function(){
    $source = $("#source").combo({  
        data: [{
            id: "0",
            name: "店主账号"
        },{
            id: "1",
            name: "分佣比例"
        },{
            id: "2",
            name: "店铺名称"
        }],
        value: "id",
        text: "name",
        width: 110
    }).getCombo();
    $shop_class_bind_enable =$("#shop_class_bind_enable").combo({
        data: [{
            id: "0",
            name: "全部"
        },{
            id: "2",
            name: "启用"
        },{
            id: "1",
            name: "未启用"
        }],
        value: "id",
        text: "name",
        width: 110
    }).getCombo();
    
    THISPAGE.init();
    
});
