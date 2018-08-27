var queryConditions = {
       
    },  
    hiddenAmount = false, 
    SYSTEM = system = parent.SYSTEM;
var THISPAGE = {
    init: function(data){
        if (SYSTEM.isAdmin === false && !SYSTEM.rights.AMOUNT_COSTAMOUNT) {
            hiddenAmount = true;
        };
        this.mod_PageConfig = Public.mod_PageConfig.init('log_list');//页面配置初始化
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
//            {name:'operating', label:'操作', width:40, fixed:true, formatter:operFmatter, align:"center"},
            {name:'grade_log_id', label:'日志id', width:100, align:"center"},
         
            {name:'user_id', label:'会员id', width:100,align:'center'},
            {name:'user_name', label:'会员名称', width:150,align:'center'},
            {name:'grade_log_grade', label:'经验值', width:100, align:"center"},
            {name:'grade_log_time', label:'操作时间', width:150, align:"center"},
            {name:'grade_log_desc', label:'操作描述', width:150, align:"center"}
            
        ];
        this.mod_PageConfig.gridReg('grid', colModel);
        colModel = this.mod_PageConfig.conf.grids['grid'].colModel;
        $("#grid").jqGrid({
            url:SITE_URL +  '?ctl=User_Grade&met=getGradeList&typ=json',
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
            rowNum: 10,
            rowList:[10,20,50], 
            viewrecords: true,
            shrinkToFit: false,
            forceFit: true,
            jsonReader: {
              root: "data.items", 
              records: "data.records",  
              repeatitems : false,
              total : "data.total",
              id: "grade_log_id"
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
        
    
//        function operFmatter (val, opt, row) {
//            var html_con = '<div class="operating" data-id="' + row.id+ '">--</div>';
//            return html_con;
//        };

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
		//添加
		$("#btn-add").click(function (t)
		{
			t.preventDefault();
			Business.verifyRight("INVLOCTION_ADD") && handle.add("id")
		});
		//查询
		 $('#search').click(function(){
            
            queryConditions.search_name = _self.$_searchName.val() === '请输入相关数据...' ? '' : _self.$_searchName.val();
            queryConditions.user_type = $source.getValue();
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
  add: function (t, e)
    {
        f = 'complain-progress';
        parent.tab.addTabItem({
            tabid: f,
            text: '等级设置',
            url: SITE_URL + '?ctl=User_Grade&met=setGrade'
        })
    }
       
};
$(function(){
  $source = $("#source").combo({
        data: [{
            id: "1",
            name: "会员id"
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
