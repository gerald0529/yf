var queryConditions = {
        points_goods_name: ''
    },  
    hiddenAmount = false, 
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('other-income-list');//页面配置初始化
        this.initDom();
        this.loadGrid();            
        this.addEvent();
    },
    initDom: function(){
        this.$_points_goods_name = $('#points_goods_name');
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        var colModel = 
		[
            {name:'operating', label:'操作', width:60, fixed:true, formatter:operFmatter, align:"center"},
            {name:'points_goods_name', label:'礼品名称', width:200, align:"center"},
            {name:'points_goods_image', label:'礼品图片',  width:100, align:"center",formatter:online_imgFmt,classes:"points_goods_image"},
            {name:'points_goods_points', label:'兑换积分', width:100,align:'center'},
            {name:'points_goods_price', label:'礼品原价', width:100, align:"center"},
            {name:'points_goods_storage', label:'库存', width:100,align:"center"},
            {name:'points_goods_view', label:'浏览',  width:100, align:"center"},
            {name:'points_goods_salenum', label:'售出',  width:100, align:"center"},
            {name:'points_goods_shelves_label', label:'上架',  width:100, align:"center"},
            {name:'points_goods_recommend_label', label:'推荐',  width:100, align:"center"}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL +  '?ctl=Promotion_Points&met=getPointsGoodsList&typ=json',
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            height:Public.setGrid().h,
            altRows: true, //设置隔行显示
            gridview: true,
            //multiselect: true,
              multiselect: false,
            multiboxonly: true,
            colModel:colModel,
            cmTemplate: {sortable: false, title: false},
            page: 1, 
            sortname: 'points_goods_id',    
            sortorder: "ASC",
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
              id: "points_goods_id"
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
            var html_con = '<div class="operating" data-id="' + row.points_goods_id + '"><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-pencil" title="编辑"></span></div>';
            return html_con;
        };
        function online_imgFmt(val, opt, row){
            if(val)
            {
                val = '<img src="'+val+'" height=60>';
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
        //刷新
        $("#btn-refresh").click(function (t)
        {
            THISPAGE.reloadData('');
            _self.$_points_goods_name.val('');
        });
        //编辑
        $('.grid-wrap').on('click', '.ui-icon-pencil', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.operate("edit", e)
        });
        //删除

        $(".grid-wrap").on("click", ".ui-icon-trash", function (e)
        {
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.del(e)
        });


        //批量删除
        $('.wrapper').on('click', '#btn-batchDel', function(e){
            if (!Business.verifyRight('QTSR_DELETE')) {
                e.preventDefault(); 
                return ;
            };
            var arr_ids = $('#grid').jqGrid('getGridParam','selarrrow')
            var voucherIds = arr_ids.join();
            if (!voucherIds) {
                parent.Public.tips({type:2,content:"请先选择需要删除的项！"});
                return;
            }
            $.dialog.confirm('您确定要删除选中的其他收入单吗？', function(){
                Public.ajaxPost('./admin.php?ctl=Finance_OtherIncome&met=deleteInc',{"id":voucherIds}, function(data){
                    if(data.status === 200 && data.msg && data.msg.length) {
                        var result = '<p>操作成功！</p>';
                        for(var resultItem in data.msg){
                            if(typeof data.msg[resultItem] === 'function') continue;//兼容ie8
                            resultItem = data.msg[resultItem];
                            result += '<p class="'+ (resultItem.isSuccess == 1 ? '':'red') +'">其他收入单［'+ resultItem.id +'］删除' + (resultItem.isSuccess == 1 ? '成功！' : '失败：'+ resultItem.msg)+'</p>';
                        }
                        parent.Public.tips({content : result});
                    } else {
                        parent.Public.tips({type: 1, content : data.msg});
                    }
                    $('#search').trigger('click');
                });
            });
        });
        //搜索
        $('#search').click(function()
        {
            queryConditions.points_goods_name = $.trim(_self.$_points_goods_name.val());
            THISPAGE.reloadData(queryConditions);
        });
        //添加积分换购商品
        $('#btn-add').click(function(e)
        {
            e.preventDefault();
            handle.operate("add");
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
            var i = _("添加礼品"), a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = _("编辑礼品"); a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
        }
       /* parent.tab.addTabItem({
            tabid: 'points-goods-manage',
            text: i,
            url: SITE_URL + '?ctl=Promotion_Points&met=managePointsGoods&typ=e&id=' + e
        })*/

        $.dialog({
            title: i,
            content: "url:"+SITE_URL + '?ctl=Promotion_Points&met=managePointsGoods&typ=e&id=' + e,
            data: a,
            // width: $(window).width() * 0.9,
            // height: $(window).height() * 0.98,
            width:1000,
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
        a[t.points_goods_id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.points_goods_id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.member_id, t, "last");
            i && i.api.close()
        }
    },
    del: function (t)
    {
        $.dialog.confirm("删除的礼品将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + '?ctl=Promotion_Points&met=removePointsGoods&typ=json', {points_goods_id: t}, function (e)
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
    }
};
$(function(){
    /*$source = $("#source").combo({
        data: [{
            id: "1",
            name: "礼品名称"
        }],
        value: "id",
        text: "name",
        width: 110
    }).getCombo();*/

    THISPAGE.init();
    
});
