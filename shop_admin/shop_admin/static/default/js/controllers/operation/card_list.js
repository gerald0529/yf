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
        this.mod_PageConfig = Public.mod_PageConfig.init('other-income-list');//页面配置初始化
        this.initDom();
        this.loadGrid();
        this.addEvent();
    },
    initDom: function(){
        /*this.$_cardName = $('#card_name').val();
        this.$_beginDate = $('#start_time').val();
        this.$_endDate = $('#end_time').val();
        this.$_cardName.placeholder();
        this.$_beginDate.datepicker();
        this.$_endDate.datepicker();*/
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        /*queryConditions.beginDate = this.$_beginDate.val();
        queryConditions.endDate = this.$_endDate.val();*/
        var colModel = [
            {name:'operating', label:'操作', width:120, fixed:true, formatter:operFmatter,align:"center"},
            {name:'card_id', label:'卡号', width:120, align:"center"},
            /*{name:'card_image', label:'图片', width:140,align:'center',formatter:online_imgFmt ,classes:"card_img"},*/
            {name:'image', hidden:true},
            {name:'card_name', label:'卡名称', width:110, align:"center"},
            // {name:'app', label:'适用平台', width:100,align:"center"},
            {name:'card_cprize', label:'内容',  width:300, align:"center"},
            {name:'card_num', label:'数量',  width:60, align:"center"},
            {name:'card_used_num', label:'已使用',  width:60, align:"center"},
            {name:'card_new_num', label:'未使用',  width:60, align:"center"},
            {name:'card_desc', label:'描述', width:328, align:'left'},
            {name:'card_start_time', label:'开始时间', width:100, align:'center'},
            {name:'card_end_time', label:'结束时间', width:100, align:'center'},
            {name:'app_id', hidden:true},
            {name:'point', hidden:true},
            {name:'money', hidden:true}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url: SITE_URL +'?ctl=Operation_Card&met=getCardList&typ=json',
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            height: Public.setGrid().h,
            altRows: true, //设置隔行显示
            gridview: true,
            // multiselect: true,  //设置多选
            multiselect: false,
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
                id: "card_id"
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
            var html_con = '<div class="operating" data-id="' + row.card_id + '"><span class="ui-icon ui-icon-search" title="查看"></span><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';

            return html_con;
        };
        function online_imgFmt(val, opt, row){
            val = '<img src="'+val+'" height=100>';
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
        // //批量删除
        //     $('.wrapper').on('click', '#btn-batchDel', function(e){
        //         if (!Business.verifyRight('QTSR_DELETE')) {
        //             e.preventDefault();
        //             return ;
        //         };
        //         var arr_ids = $('#grid').jqGrid('getGridParam','selarrrow')
        //         var voucherIds = arr_ids.join();
        //         if (!voucherIds) {
        //             parent.Public.tips({type:2,content:"请先选择需要删除的项！"});
        //             return;
        //         }
        //         //Public.ajaxPost('./admin.php?ctl=Finance_OtherIncome&met=deleteInc',{"id":voucherIds}, function(data){
        //         $.dialog.confirm('您确定要删除选中的吗？', function(){
        //             Public.ajaxPost('./admin.php?ctl=Paycen_PayCard&met=removeBaseSelected',{"id":voucherIds}, function(data){
        //                 if(data.status === 200 && data.msg && data.msg.length) {
        //                     var result = '<p>操作成功！</p>';
        //                     for(var resultItem in data.msg){
        //                         if(typeof data.msg[resultItem] === 'function') continue;//兼容ie8
        //                         resultItem = data.msg[resultItem];
        //                         result += '<p class="'+ (resultItem.isSuccess == 1 ? '':'red') +'">其他收入单［'+ resultItem.id +'］删除' + (resultItem.isSuccess == 1 ? '成功！' : '失败：'+ resultItem.msg)+'</p>';
        //                     }
        //                     parent.Public.tips({content : result});
        //                 } else {
        //                     parent.Public.tips({type: 1, content : data.msg});
        //                 }
        //                 $('#search').trigger('click');
        //             });
        //         });
        //     });
        //搜索
        $('#search').click(function(){
            queryConditions.cardName = $('#card_name').val() === '购物卡名称' ? '' : $('#card_name').val();
            queryConditions.beginDate = $('#start_time').val();
            queryConditions.endDate = $('#end_time').val();
            //queryConditions.appid = $source.getValue() === '请选择平台' ? '' : $source.getValue();

            THISPAGE.reloadData(queryConditions);
        });
        //跳转购物卡详情
        $('.grid-wrap').on('click', '.ui-icon-search', function(e){
            e.preventDefault();
            var card_id = $(this).parent().data("id");
            $.dialog({
                title: "查看购物卡详情",
                content: "url:"+ SITE_URL + '?ctl=Operation_Card&met=getDetail&id=' + card_id,
                width:945,
                height: $(window).height(),
                max: !1,
                min: !1,
                cache: !1,
                lock: !0

            })

        });
        //***************************************
        $("#add").click(function (e)
        {
            e.preventDefault();
            Business.verifyRight("QTSR_ADD") && handle.operate("add")
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
            var i = "新增购物卡", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "修改购物卡", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
            console.info(a);
        }
        $.dialog({
            title: i,
            content: "url:./index.php?ctl=Operation_Card&met=manageCard",
            data: a,
            width:1000,
            height: 345,
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
        a[t.card_id] = t;
        if ("edit" == e)
        {
            //$("#grid").jqGrid("setRowData", t.card_id, t);
            $("#grid").jqGrid().trigger("reloadGrid");
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.card_id, t, "last");
            i && i.api.close()
        }
    }, del: function (t)
    {
        $.dialog.confirm("删除的购物卡将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost("./index.php?ctl=Operation_Card&met=delCard&typ=json", {card_id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "购物卡删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "购物卡删除失败！" + e.msg})
                }
            })
        })
    }
};

$(function(){
    $('#start_time').datetimepicker({
        controlType: 'select',
        format:"Y-m-d",
        timepicker:false
    });

    $('#end_time').datetimepicker({
        controlType: 'select',
        format:"Y-m-d",
        timepicker:false
    });

    $source = $("#source").combo({
        data: [{
            id: "0",
            name: "请选择平台"
        },{
            id: "9999",
            name: "通用"
        }, {
            id: "101",
            name: "MallBuilder"
        }, {
            id: "102",
            name: "ShopBuilder"
        }, {
            id: "103",
            name: "ImBuilder"
        }],
        value: "id",
        text: "name",
        width: 180
    }).getCombo();

    THISPAGE.init();

});
