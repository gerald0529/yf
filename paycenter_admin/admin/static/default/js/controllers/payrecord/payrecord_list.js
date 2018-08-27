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
        this.$_userName = $('#userName');
        this.$_payOrder = $('#payOrder');
        this.$_beginDate = $('#beginDate').val(system.beginDate);
        this.$_endDate = $('#endDate').val(system.endDate);
        this.$_userName.placeholder();
        this.$_payOrder.placeholder();
        this.$_beginDate.datepicker();
        this.$_endDate.datepicker();        
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        queryConditions.beginDate = this.$_beginDate.val();
        queryConditions.endDate = this.$_endDate.val();
        var colModel = [
            {name:'order_id', label:'商户订单id', width:200, align:"center"},
            {name:'user_nickname', label:'用户帐号', width:150,align:'center'},
            {name:'record_money',label:'金额',  width:150,align:'center'},
            {name:'record_title', label:'标题', width:110, align:"center"},
            {name:'trade_type', label:'交易类型', width:110, align:"center"},
            {name:'user_type_con', label:'用户类型', width:110, align:"center"},
            {name:'record_status_con', label:'付款状态', width:110, align:"center"},
            {name:'record_payorder', label:'支付单号', width:200, align:"center"},
            {name:'record_paytime', label:'支付时间', width:80, align:"center"},
            {name:'record_date', label:'日期', width:110, align:"center"},
            {name:'record_desc', label:'描述', width:280, align:"center"},
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url: SITE_URL +'?ctl=Paycen_PayRecord&met=getRecordList&typ=json',
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
              id: "consume_record_id"
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
            var html_con = '<div class="operating" data-id="' + row.consume_record_id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
            return html_con;
        };

    },
    reloadData: function(data){
        $("#grid").jqGrid('setGridParam',{postData: data}).trigger("reloadGrid");
    },
    addEvent: function(){
        var _self = this;
    
     
        $('#search').click(function(){
            queryConditions.userName = _self.$_userName.val() === '请输入用户账户' ? '' : _self.$_userName.val();
            queryConditions.payOrder = _self.$_payOrder.val() === '请输入支付单号' ? '' : _self.$_payOrder.val();
//            queryConditions.beginDate = _self.$_beginDate.val();
//            queryConditions.endDate = _self.$_endDate.val();
            queryConditions.trade_type_id = $source.getValue() === '交易类型' ? '' : $source.getValue();

            THISPAGE.reloadData(queryConditions);
        });
        
      
        
        
        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};

$(function(){
    $source = $("#source").combo({
        data: [{
            id: "0",
            name: "交易类型"
        },{
            id: "1",
            name: "购物"
        }, {
            id: "2",
            name: "转账 "
        }, {
            id: "3",
            name: "充值"
        }, {
            id: "4",
            name: "提现"
        }, {
            id: "5",
            name: "退款"
        }],
        value: "id",
        text: "name",
        width: 180
    }).getCombo();

    THISPAGE.init();
    
});
