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
        this.$_cardName = $('#cardName');
        this.$_beginDate = $('#beginDate').val(system.beginDate);
        this.$_endDate = $('#endDate').val(system.endDate);
        this.$_cardName.placeholder();
        this.$_beginDate.datepicker();
        this.$_endDate.datepicker();
    },

    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        queryConditions.beginDate = this.$_beginDate.val();
        queryConditions.endDate = this.$_endDate.val();
        var colModel = [
            // {name:'operating', label:'操作', width:80, fixed:true, formatter:operFmatter,align:"center"},
            {name:'card_id', label:'卡号', width:120, align:"center"},
            {name:'user_id', label:'用户id', width:100, align:'center'},
            {name:'image', hidden:true},
            {name:'card_password', label:'卡片密码', width:110, align:"center"},
            {name:'card_code', label:'卡片激活码',  width:100, align:"center"},
            {name:'card_fetch_time', label:'领奖时间',  width:150, align:"center"},
            {name:'card_media_id', label:'媒体id',  width:60, align:"center"},
            {name:'server_id', label:'服务器id',  width:100, align:"center"},
            {name:'user_account', label:'领卡人账号', width:150, align:'center'},
            {name:'card_time', label:'卡牌生成时间', width:160, align:'center'},
            {name:'card_money', label:'购物卡余额(元)', width:100, align:'center'}

        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url: SITE_URL +'?ctl=Paycen_PayInfo&met=getInfoList&typ=json',
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            height: gridWH.h,
            altRows: true, //设置隔行显示
            gridview: true,
            // multiselect: true,  //设置多选
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
                id: "card_code"
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
            //<span class="ui-icon ui-icon-search" title="查看"></span>
            var html_con = '<div class="operating" data-id="' + row.card_code + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';

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

        //搜索
        $('#search').click(function(){
            // queryConditions.cardName = _self.$_cardName.val() === '请输入卡号' ? '' : _self.$_cardName.val();
            queryConditions.beginDate = _self.$_beginDate.val();
            // queryConditions.endDate = _self.$_endDate.val();
            queryConditions.cardName = $source.getValue() === '请选择卡号' ? '' : $source.getValue();
            THISPAGE.reloadData(queryConditions);
        });
        //跳转购物卡详情
        $('.grid-wrap').on('click', '.ui-icon-search', function(e){
            e.preventDefault();
            var card_code = $(this).parent().data("id");

            $.dialog({
                title: "查看购物卡详情",
                content: "url:"+ SITE_URL + '?ctl=Paycen_PayCard&met=getCardlist&card_code=' + card_code,
                width: 950,
                height: $(window).height() * 0.9,
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
            var i = "新增购物卡", a = {oper: t, callback: this.callback, card_row:card_row};
        }
        else
        {
            var i = "修改购物卡", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
        }
        $.dialog({
            title: i,
            content: "url:./index.php?ctl=Paycen_PayCard&met=addmanage",
            data: a,
            width: 500,
            height: 250,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
    }, callback: function (t, e, i)
    {
        /*var a = $("#grid").data("gridData");
        if (!a)
        {
            a = {};
            $("#grid").data("gridData", a)
        }
        a[t.member_id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.member_id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.member_id, t, "last");
            i && i.api.close()
        }*/
        window.location.reload();
    }, del: function (t)
    {
        $.dialog.confirm("删除的购物卡将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost("./index.php?ctl=Paycen_PayInfo&met=remove&typ=json", {card_code: t}, function (e)

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
//显示数据
//************************************************

//************************************************
$(function(){
    $source = $("#source").combo(card_list_row).getCombo();
    THISPAGE.init();

});

//************************************************


