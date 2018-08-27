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
            {name:'shop_name', label:'店铺名称', width:250,align:'center',"formatter": handle.linkShopFormatter},
            {name:'shop_grade_name', label:'店铺等级', width:100, align:"center"},
            {name:'shop_grade_fee', label:'收费等级', width:100, align:"center"},
            {name:'renew_time', label:'续费时长', width:100, align:"center"},
            {name:'renew_cost', label:'应付金额', width:100, align:"center"},
            {name:'renewal_status_cha', label:'续签状态',  width:100, align:"center"},
            {name:'create_time', label:'申请时间',  width:100, align:"center"},
            {name:'start_time', label:'有效期开始时间',  width:150, align:"center"},
            {name:'end_time', label:'有效期结束时间',  width:150, align:"center"}

               
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL +  "?ctl=Shop_Manage&met=reopenlist&typ=json",
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
              id: "id"
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
            if(row.status==0){
            var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-pencil" title="审核"></span></div>';
            }else{
               var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-pencil ui-icon-disabled" data-dis="1" title="审核"></span></div></div>';
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
        //编辑
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
        
                  //跳转到店铺认证信息页面
        $('#grid').on('click', '.to-shop', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var shop_id = $(this).attr('data-id');
            $.dialog({
                title: '查看店铺信息',
                content: "url:"+SITE_URL + '?ctl=Shop_Manage&met=getShoplist&shop_id=' + shop_id,
                width: 1000,
                height:$(window).height(),
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
        $.dialog.confirm("删除将不能恢复，确认删除吗？", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Shop_Manage&met=delReopen&typ=json", {id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "删除失败！" + e.msg})
                }
            })
        })
    },
    status: function (t)
    {
        $.dialog.confirm("审核通过续签", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Shop_Manage&met=examineReopen&typ=json", {id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "成功！"});
                    location.href= SITE_URL + "?ctl=Shop_Manage&met=reopen"; 
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
            name: "店主id"
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
