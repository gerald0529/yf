$(function () {

    searchFlag = false;
    SYSTEM = system = parent.SYSTEM;
    function initDom() {
        var defaultPage = Public.getDefaultPage();
        defaultPage.SYSTEM = defaultPage.SYSTEM || {};
        defaultPage.SYSTEM.categoryInfo = defaultPage.SYSTEM.categoryInfo || {};

        this.$_matchCon = $('#matchCon'),
            this.$_beginDate = $('#begin_date').val(system.beginDate),
            this.$_endDate = $('#end_date').val(system.endDate),
            this.$_matchCon.placeholder(),
            this.$_beginDate.datepicker(),
            this.$_endDate.datepicker()

    };

    function initGrid() {
        var a = Public.setGrid(),
            l = [
                {name:'operating', label:'操作', width:40, fixed:true, formatter:operFmatter, align:"center"},
                {name:'name', label:'模板描述', width:300, align:"center"},

                {name:'is_mail', label:'站内信', width:100,align:'center'},
                {name:'is_phone', label:'手机短信', width:100,align:'center'},
                {name:'is_email', label:'邮件', width:100, align:"center"},
                {name:'baidu_tpl_id', label:'百度模板ID', width:100, align:"center"}

            ];
            
            $('#grid').jqGrid({
                url: './index.php?ctl=Message&met=getMessageList&typ=json',
                datatype: 'json',
                autowidth: true,
                shrinkToFit: true,
                forceFit: false,
                width: a.w,
                height: a.h,
                altRows: true,
                gridview: true,
                onselectrow: false,
                multiselect: false, //多选
                colModel: l,
                pager: '#page',
                viewrecords: true,
                cmTemplate: {
                    sortable: false
                },
                rowNum: 100,
                rowList: [100, 200, 500],
                jsonReader: {
                    root: "data.items",
                    records: "data.records",
                    total: "data.total",
                    repeatitems: false,
                    id: "id"
                },

                loadComplete: function(response) {
                    if (response && response.status == 200) {
                        var gridData = {};
                        data = response.data;
                        for (var i = 0; i < data.items.length; i++) {
                            var item = data.items[i];
                            item['id'] = item.id;
                            gridData[item.id] = item;
                        }
                        $("#grid").data('gridData', gridData);
                    } else {
                        var msg = response.status === 250 ? (searchFlag ? '没有满足条件的结果哦！' : '没有数据哦！') : response.msg;
                        parent.Public.tips({
                            type: 2,
                            content: msg
                        });
                    }
                },
                loadError: function(xhr, status, error) {
                    parent.Public.tips({
                        type: 1,
                        content: '操作失败了哦，请检查您的网络链接！'
                    });
                },
            }).navGrid('#page', {
                edit: false,
                add: false,
                del: false,
                search: false,
                refresh: false
            }).navButtonAdd('#page', {
                caption: '',
                buttonicon: 'ui-icon-config',
                onClickButton: function () {
                    j.config()
                },
                position: 'last'
            });
            function operFmatter (val, opt, row) {
                var html_con = '<div class="operating" data-id="' + row.id+ '"><span class="ui-icon ui-icon-pencil" title="编辑"></span></div>';
                return html_con;
            };
    }

    function initEvent() {
         //编辑
        $('.grid-wrap').on('click', '.ui-icon-pencil', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");

            handle.operate("edit", e)
        });
        
        $(window).resize(function(){
            Public.resizeGrid();
        });
    }

    var handle = {
	operate: function (t, e)
        {
            var i = "编辑消息通知", a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};
            $.dialog({
                title: i,
                content: "url:"+SITE_URL + '?ctl=Message&met=getTemplateInfo&id=' + e + '&typ=e',
                data: a,
                width: 830,
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
    initDom();
    initGrid();
    initEvent();
});
