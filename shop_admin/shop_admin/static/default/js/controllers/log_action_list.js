$(function() {
    var searchFlag = false;
    var filterClassCombo, userCombo;
    var handle = {
    };


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


        userCombo = Business.categoryCombo($('#user'), {
            editable: false,
            extraListHtml: '',
            addOptions: {
                value: -1,
                text: '选择用户'
            },
            defaultSelected: 0,
            trigger: true,
            width: 120
        }, 'user');
    };

    function initGrid() {
        var grid_row = Public.setGrid();
        var colModel = [{
            "name": "log_id",
            hidden : true,
            "index": "log_id",
            "label": "日志id",
            "classes": "ui-ellipsis",
            "align": "left",
            "title": false,
            "fixed": true,
            "width": 60
        }, {
            "name": "user_id",
            "index": "user_id",
            "label": "用户Id",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 100
        }, {
            "name": "user_account",
            "index": "user_account",
            "label": "用户账号",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "user_name",
            "index": "user_name",
            hidden : true,
            "label": "用户名称",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "action_id",
            "index": "action_id",
            "label": "行为id",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 100
        }, {
            "name": "log_param",
            "index": "log_param",
            "label": "请求的参数",
            "classes": "ui-ellipsis",
            "align": "left",
            "width": 260,
            "title": false
        }, {
            "name": "log_ip",
            "index": "log_ip",
            "label": "操作IP",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "log_time",
            "index": "log_time",
            "label": "记录时间",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width":200
        }];
        //mod_PageConfig.gridReg('grid', colModel);
        //colModel = mod_PageConfig.conf.grids['grid'].colModel;
        $('#grid').jqGrid({
            url: SITE_URL + '?ctl=Log_Action&met=lists&typ=json',
            datatype: 'json',
            autowidth: true,
            shrinkToFit: true,
            forceFit: false,
            width: grid_row.w,
            height: grid_row.h,
            altRows: true,
            gridview: true,
            onselectrow: false,
            multiselect: false, //多选
            colModel: colModel,
            pager: '#page',
            viewrecords: true,
            cmTemplate: {
                sortable: false
            },
            rowNum: 100,
            rowList: [100, 200, 500],
            //scroll: 1,
            jsonReader: {
                root: "data.items",
                records: "data.records",
                total: "data.total",
                repeatitems: false,
                id: "action_id"
            },
            loadComplete: function(response) {
                if (response && response.status == 200) {
                    var gridData = {};
                    data = response.data;
                    for (var i = 0; i < data.items.length; i++) {
                        var item = data.items[i];
                        item['id'] = item.action_id;
                        gridData[item.action_id] = item;
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
            resizeStop: function(newwidth, index) {
                //mod_PageConfig.setGridWidthByIndex(newwidth, index, 'grid');
            }
        }).navGrid('#page', {
            edit: false,
            add: false,
            del: false,
            search: false,
            refresh: false
        });
    }

    function initEvent() {
        var match_con = $('#matchCon');
        //查询
        $('#search').on('click', function(e) {
            e.preventDefault();
            var skey = match_con.val() === '请输入查询内容' ? '' : $.trim(match_con.val());
            var begin_date = $.trim($('#begin_date').val());
            var end_date = $.trim($('#end_date').val());


            var user_id = userCombo ? userCombo.getValue() : -1;
            $("#grid").jqGrid('setGridParam', {
                page: 1,
                postData: {
                    skey: skey,
                    user_id: user_id,
                    begin_date: begin_date,
                    end_date: end_date,
                }
            }).trigger("reloadGrid");

        });

        //导入
        $('#btn-import').on('click', function(e) {
            e.preventDefault();

            parent.$.dialog({
                width: 560,
                height: 300,
                title: '批量导入',
                content: 'url:/import.jsp',
                lock: true
            });
        });
    }

    //var mod_PageConfig = Public.mod_PageConfig.init('customerList');//页面配置初始化
    initDom();
    initGrid();
    initEvent();
});