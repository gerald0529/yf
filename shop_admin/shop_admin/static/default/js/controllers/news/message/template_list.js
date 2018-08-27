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
        this.$_searchName = $('#searchName');
    },
    loadGrid: function(){
        var gridWH = Public.setGrid(), _self = this;
        var colModel = [
            {name:'operating', label:'操作', width:40, fixed:true, formatter:operFmatter, align:"center"},
            {name:'name', label:'模板描述', width:300, align:"center"},
         
            {name:'is_mail', label:'站内信', width:100,align:'center'},
            {name:'is_phone', label:'手机短信', width:100,align:'center'},
            {name:'is_email', label:'邮件', width:100, align:"center"},
            {name:'baidu_tpl_id', label:'百度模板ID', width:100, align:"center"}
           
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL +  '?ctl=News_Message&met=getMessageList&typ=json&type=' + template_type,
            postData: queryConditions,
            datatype: "json",
            autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
            height: Public.setGrid().h,
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
            forceFit: false,
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
        
    
        function operFmatter (val, opt, row) {
            var html_con = '<div class="operating" data-id="' + row.id+ '"><span class="ui-icon ui-icon-pencil" title="编辑"></span></div>';
            return html_con;
        };

        function online_imgFmt(val, opt, row){
            if(val)
            {
                val = '<img src="'+val+'" height=100>';
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
       $("#btn-refresh").click(function ()
        {
            THISPAGE.reloadData('');
            _self.$_searchName.placeholder('请输入相关数据...');
            _self.$_searchName.val('');
        });
        $('#add').click(function(e){
            e.preventDefault();
            if (!Business.verifyRight('QTSR_ADD')) {
                return ;
            };
            parent.tab.addTabItem({tabid: 'money-otherIncome', text: '消息模板', url: './admin.php?ctl=Finance_OtherIncome'});
        });
        
        
        $(window).resize(function(){
            Public.resizeGrid();
        });
    }
};
/* var handle = {
  operate: function (t, e)
    {
        f = 'complain-progress';
        parent.tab.addTabItem({
            tabid: f,
            text: '编辑消息通知',
            url: SITE_URL + '?ctl=News_Message&met=getTemplateInfo&id=' + e + '&type=' + template_type
        })
    }
}; */
var handle = {
	operate: function (t, e)
    {
		var i = "编辑消息通知", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
        $.dialog({
            title: i,
            content: "url:"+SITE_URL + '?ctl=News_Message&met=getTemplateInfo&id=' + e + '&type=' + template_type,
            data: a,
            width: 830,
            // height: $(window).height()*0.9,
            height:510,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
       
    }, callback: function (t, e, i)
    {
         window.location.reload(); 	
    }
 
};
$(function(){
  

    THISPAGE.init();
    
});
