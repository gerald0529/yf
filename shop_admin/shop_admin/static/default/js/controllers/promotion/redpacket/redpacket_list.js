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
        this.mod_PageConfig = Public.mod_PageConfig.init('redpacket-list');//页面配置初始化

        this.loadGrid();
        this.addEvent();
    },

    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        var colModel = [
            {name:'redpacket_code', label:'红包编码', width:250, align:"center"},
            {name:'redpacket_state_label', label:'使用状态', width:100, align:"center"},
            {name:'redpacket_owner_name', label:'所属会员',  width:120, align:"center"},
            {name:'redpacket_active_date', label:'领取时间',  width:150, align:"center"}
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url: SITE_URL + '?ctl=Promotion_RedPacket&met=getRedPacketListByTempID&typ=json&redpacket_t_id='+data.id,
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
            sortname: 'redpacket_id',
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
                id: "redpacket_id"
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
    },

    reloadData: function(data){
        $("#grid").jqGrid('setGridParam',{postData: data}).trigger("reloadGrid");
    },
    addEvent: function(){
        var _self = this;

        //刷新
        $("#btn-refresh").click(function ()
        {
            THISPAGE.reloadData('');
            _self.$_redpacket_title.val('');
        });

        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};

$(function(){
    THISPAGE.init();
});

api = frameElement.api;
data = api.data;
console.info(data);