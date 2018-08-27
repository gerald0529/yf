/**
 * Created by Administrator on 2016/5/18.
 */

var queryConditions = {

    },
    hiddenAmount = false,
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('discount-goods-list');//页面配置初始化

        this.loadGrid();
        this.addEvent();
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        var colModel = [
            {name:'operating', label:'操作', width:50, fixed:true, formatter:operFmatter, align:"center"},
            {name:'goods_name', label:'商品名称', width:250, align:"center"},
            {name:'goods_image', label:'商品图片', width:60, align:"center",formatter:online_imgFmt,classes:"discount_goods_image"},
            {name:'goods_price', label:'商品价格（元）',  width:120, align:"center"},
            {name:'discount_price', label:'折扣价格（元）',  width:120, align:"center"},
            {name:'discount_percent', label:'折扣率',  width:100, align:"center"}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url: SITE_URL + '?ctl=Promotion_Discount&met=getDiscountGoodsListById&typ=json&id='+data.id,
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
            sortname: 'discount_goods_id',
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
                id: "discount_goods_id"
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
            var html_con = '<div class="operating" data-id="' + row.discount_goods_id + '"><a href= '+ SHOP_URL+'?ctl=Goods_Goods&met=goods&gid='+row.goods_id+' target="_blank"><span class="ui-icon ui-icon-search" title="查看"></span></a></div>';
            return html_con;
        };
        function online_imgFmt(val, opt, row){
            if(val)
            {
                val = '<img src="'+val+'" height=30>';
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
        $('.grid-wrap').on('click', '.ui-icon-pencil', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.operate("edit", e)
        });

        //刷新
        $("#btn-refresh").click(function ()
        {
            THISPAGE.reloadData('');
            _self.$_discount_name.val('');
            _self.$_shop_name.val('');
        });

        //删除
        $("#grid").on("click", ".operating .ui-icon-trash", function (t)
        {
            t.preventDefault();
            var e = $(this).parent().data("id");
            handle.del(e)
        });


        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};

var handle = {
    operate: function (t, e)
    {
        if ("edit" == t)
        {
            var i = "店铺满即送活动详情", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
            //console.info(a);
        }
        $.dialog({
            title: i,
            content: "url:"+SITE_URL + '?ctl=Promotion_Discount&met=',
            data: a,
            width: 600,
            height: 280,
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
        a[t.discount_goods_id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.discount_goods_id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.discount_goods_id, t, "last");
            i && i.api.close()
        }
    }, del: function (t)
    {
        $.dialog.confirm("删除的活动将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + '?ctl=Promotion_Discount&met=removeDiscountActivity&typ=json', {xian_shi_id: t}, function (e)
            {
                //alert(JSON.stringify(e));
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "活动删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "活动删除失败！" + e.msg})
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
    THISPAGE.init();


});

api = frameElement.api;
data = api.data;
console.info(data);