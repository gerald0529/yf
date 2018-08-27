var queryConditions = {
        points_order_rid: '',
        points_buyername: ''
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
        this.$_points_order_rid = $('#points_order_rid');
        this.$_points_buyername = $('#points_buyername');
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        var colModel = 
		[
            {name: "operate", label:'操作', width: 85, fixed: true, align: "center", formatter: operFmatter} ,
            {name:'points_order_rid', label:'兑换单号', width:200, align:"center"},
            {name:'points_buyername', label:'会员名称',  width:200, align:"center"},
            {name:'points_allpoints', label:'兑换积分', width:100,align:'center'},
            {name:'points_addtime', label:'兑换时间', width:150, align:"center"},
            {name:'points_orderstate_label', label:'状态', width:100,align:"center"}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL +  '?ctl=Promotion_Points&met=getPointsOrderList&typ=json',
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
            sortname: 'points_order_id',    
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
              id: "points_order_id"
            },
            loadComplete: function (t)
            {
                if (t && 200 == t.status)
                {
                    var e = {};
                    t = t.data;
                    for (var i = 0; i < t.items.length; i++)
                    {
                        var a = t.items[i];
                        e[a.points_order_id] = a;
                    }
                    $("#grid").data("gridData", e);

                    0 == t.items.length && parent.Public.tips({type: 2, content: "没有类型数据！"})
                }
                else
                {
                    parent.Public.tips({type: 2, content: "获取类型数据失败！" + t.msg})
                }
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
            var html_con = '';
            if(row.points_orderstate == 1)
            {
                html_con = '<div  class="operating" data-id="' + row.points_order_id + '"><span class="ui-icon ui-icon-search" title="查看订单"></span><span class="ui-icon ui-icon-suitcase" title="发货"></span><span class="ui-icon ui-icon-close" title="取消订单"></span></div>';
            }
            else
            {
                html_con = '<div  class="operating" data-id="' + row.points_order_id + '"><span class="ui-icon ui-icon-search" title="查看订单"></span><span class="ui-icon ui-icon-disabled ui-icon-suitcase"  data-dis="1" title="发货"></span><span class="ui-icon ui-icon-disabled ui-icon-close" data-dis="1" title="取消订单"></span></div>';
            }
            return html_con;
        };

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
            _self.$_points_order_rid.val('');
            _self.$_points_buyername.val('');
        });

        //详情
        $('.grid-wrap').on('click', '.ui-icon-search', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");
            handle.operate("detail", e)
        });

        //取消订单
        $('.grid-wrap').on('click', '.ui-icon-close', function(e){
            if($(this).attr('data-dis'))
            {
                return false;
            }
            else
            {
                e.preventDefault();
                var e = $(this).parent().data("id");
                handle.operate("cancel", e)
            }
        });

        //发货
        $('.grid-wrap').on('click', '.ui-icon-suitcase', function(e){
            if($(this).attr('data-dis'))
            {
                return false;
            }
            else
            {
                e.preventDefault();
                var e = $(this).parent().data("id");
                handle.operate("edit", e)
            }
        });

        //搜索
        $('#search').click(function(){
            queryConditions.points_order_rid = $.trim(_self.$_points_order_rid.val());
            queryConditions.points_buyername = $.trim(_self.$_points_buyername.val());
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
        if("detail" == t)
        {
            parent.tab.addTabItem({
                tabid: "points-order-detail",
                text: _('积分兑换订单详情'),
                url: SITE_URL + '?ctl=Promotion_Points&met=getPointsOrderInfo&typ=e&id=' + e
            })
        }
        else if('edit' == t)
        {
            var i = "积分兑换-发货", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
            //console.info(a);
            $.dialog({
                title: i,
                content: "url:"+SITE_URL + '?ctl=Promotion_Points&met=deliver&typ=e&id=' + e,
                data: a,
                width: 650,
                height: 250,
                max: !1,
                min: !1,
                cache: !1,
                lock: !0
            })
        }
        else if('cancel' == t)
        {
            $.dialog.confirm("取消的订单将不能恢复，请确认是否取消？", function ()
            {
                Public.ajaxPost(SITE_URL + '?ctl=Promotion_Points&met=cancelPointsOrder&typ=json', {points_order_id: e}, function (d)
                {
                    //alert(JSON.stringify(e));
                    if (d && 200 == d.status)
                    {
                        parent.Public.tips({content: "操作成功！"});

                        d.data['operate'] = '';
                        console.info(d.data);
                        $("#grid").jqGrid("setRowData", e , d.data);

                    }
                    else
                    {
                        parent.Public.tips({type: 1, content: "操作失败！" + d.msg})
                    }
                })
            })
        }
    }, callback: function (t, e, i)
    {
        var a = $("#grid").data("gridData");
        if (!a)
        {
            a = {};
            $("#grid").data("gridData", a)
        }
        a[t.points_order_id] = t;
        if ("edit" == e)
        {
            t.operate = '';
            $("#grid").jqGrid("setRowData", t.points_order_id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.points_order_id, t, "last");
            i && i.api.close()
        }
    }
};

$(function(){
    $source = $("#source").combo({
        data: [{
            id: "1",
            name: "兑换单号"
        },{
            id: "2",
            name: "会员名称"
        }],
        value: "id",
        text: "name",
        width: 110
    }).getCombo();

    THISPAGE.init();
});
