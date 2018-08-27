var queryConditions = {
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
        this.$_searchContent = $('#searchContent');
        this.$_beginDate = $('#beginDate').val(system.beginDate);
        this.$_endDate = $('#endDate').val(system.endDate);
        this.$_searchContent.placeholder();
        this.$_beginDate.datepicker();
        this.$_endDate.datepicker();
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        queryConditions.beginDate = this.$_beginDate.val();
        queryConditions.endDate = this.$_endDate.val();
        var colModel = [
            {name:'consume_trade_id', label:'订单编号', width:200, align:"center"},
            {name:'user_realname', label:'真实姓名', width:100, align:"center"},
            {name:'user_nickname', label:'账号名', width:100,align:'center'},
            {name:'trade_create_time', label:'订单日期', width:160, align:"center"},
            {name:'order_payment_amount', label:'订单总金额（￥）', width:150, align:"center"},
            {name:'trade_payment_amount', label:'实际还款金额（￥）', width:150, align:"center"},
            {name:'return_remain', label:'剩余还款金额（￥）', width:150, align:"center"},
            {name:'credit_status', label:'还款状态', width:100, align:"center"},
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url: SITE_URL +'?ctl=Paycen_PayInfo&met=getBtOrderList&typ=json',
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
              id: "user_id"
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
            var html_con = '<div class="operating" data-id="' + row.user_id + '"><span class="ui-icon ui-icon-pencil" title="编辑"></span></div>';
            return html_con;
        };

 

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


        $('#search').click(function(){
            queryConditions.searchName = $searchName.getValue();
            queryConditions.searchContent = _self.$_searchContent.val();
            queryConditions.beginDate = _self.$_beginDate.val();
            queryConditions.endDate = _self.$_endDate.val();
//            queryConditions.status = $status.getValue() === '请选择审核状态' ? '' : $status.getValue();
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
        if ("add" == t)
        {
            var i = "新增购物卡", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "收款确认", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
            console.info(a);
        }
        $.dialog({
            title: i,
            content: "url:./index.php?ctl=Paycen_BtInfo&met=editCreditReturn&user_id="+e,
            data: a,
            width: 550,
            height: 100,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
            })
    }, callback: function (t, e, i)
    {
           window.location.reload(); 
    },imgFmt: function (val, opt, row)
    {
        if (val)
        {
            val = '<img src="' + val + '">';
        }
        else
        {
            if (row.user_identity_face_logo)
            {
                val = '<img height="30" width="100" src="' + row.user_identity_face_logo + '">';
            }
            else
            {
                val = '<img height="30" width="100" src="' + row.user_identity_font_logo + '">';
            }
        }
        return val;
    }
};
$(function(){
//     $status = $("#status").combo({
//        data: [{
//            id: "0",
//            name: "请选择还款状态"
//        },{
//            id: "1",
//            name: "已还款"
//        }, {
//            id: "2",
//            name: "待还款"
//        }, {
//            id: "3",
//            name: "已延期"
//        }],
//        value: "id",
//        text: "name",
//        width: 180
//    }).getCombo();

    $searchName = $("#searchName").combo({
        data: [{
            id: "user_nickname",
            name: "账号名"
        },{
            id: "user_realname",
            name: "真实姓名"
        }, {
            id: "order_id",
            name: "订单编号"
        }],
        value: "id",
        text: "name",
        width: 180
    }).getCombo();

    THISPAGE.init();
    
});
