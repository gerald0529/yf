$(function() {
    var searchFlag = false;
    var filterClassCombo, catorageCombo;
    var handle = {
        statusFormatter: function(val, opt, row) {
            var text = val == 0 ? _('绑定') : _('已绑定');
            var cls = val == 0 ? 'ui-label-default' : 'ui-label-success';
            return '<span class="set-status ui-label ' + cls + '" data-enable="' + val + '" data-id="' + row.id + '">' + text + '</span>';
        }
    };

    function initDom() {
        var defaultPage = Public.getDefaultPage();
        defaultPage.SYSTEM = defaultPage.SYSTEM || {};
        defaultPage.SYSTEM.categoryInfo = defaultPage.SYSTEM.categoryInfo || {};
        catorageCombo = Business.categoryCombo($('#catorage'), {
            editable: false,
            extraListHtml: '',
            addOptions: {
                value: -1,
                text: _('选择类别')
            },
            defaultSelected: 0,
            trigger: true,
            width: 120
        }, 'customertype');
    };

    function initGrid() {
        var grid_row = Public.setGrid();
        var colModel = [{
            "name": "msg_sender",
            "index": "msg_sender",
            "label": "编号",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 200
        }, {
            "name": "msg_receiver",
            "index": "msg_receiver",
            "label": "商家名称",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 500
        }, {
            "name": "date_created",
            "index": "date_created",
            "label": "商家手机",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 300
        }, {
            "name": "rbind",
            "index": "rbind",
            "label": "操作",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 200,
            "formatter": handle.statusFormatter
        }];
        //mod_PageConfig.gridReg('grid', colModel);
        //colModel = mod_PageConfig.conf.grids['grid'].colModel;
        $('#grid').jqGrid({
            url: SITE_URL + '?ctl=User_Message&met=getList&typ=json&is_read=2&is_group=1',
            datatype: 'json',
            autowidth: true,
            shrinkToFit:false,
            forceFit: true,
            width: grid_row.w,
            height: grid_row.h,
            altRows: true,
            gridview: true,
            onselectrow: false,
            multiselect: false, //多选
            colModel: colModel,
            cmTemplate: {
                sortable: true
            },
            sortname: "cron_id", //指定默认排序的列
            sortorder: "asc", //指定默认排序方式
            pager: '#grid-pager',
            viewrecords: true,
            rowNum: 100,
            rowList: [100, 200, 500],
            prmNames: { //向后台传递的参数,重新命名
                //page:"page.currentPage",
                //rows:"page.pageSize"
            },
            //scroll: 1,
            jsonReader: {
                root: "data.items",
                records: "data.records",
                total: "data.total",
                repeatitems: false,
                id: "cron_id"
            },
            loadComplete: function(response) {
                if (response && response.status == 200) {
                    var gridData = {};
                    data = response.data;
                    for (var i = 0; i < data.items.length; i++) {
                        var item = data.items[i];
                        item['id'] = item.cron_id;
                        gridData[item.cron_id] = item;
                    }

                    $("#grid").data('gridData', gridData);
                } else {
                    var msg = response.status === 250 ? (searchFlag ? _('没有满足条件的结果哦！') : _('没有数据哦！')) : response.msg;
                    parent.Public.tips({
                        type: 2,
                        content: msg
                    });
                }
            },
            loadError: function(xhr, status, error) {
                parent.Public.tips({
                    type: 1,
                    content: _('操作失败了哦，请检查您的网络链接！')
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
            var skey = match_con.val() === '商家名称/商家手机号' ? '' : $.trim(match_con.val());
            // var category_id = catorageCombo ? catorageCombo.getValue() : -1;
            $("#grid").jqGrid('setGridParam', {
                page: 1,
                postData: {
                    skey: skey,
                    // category_id: category_id
                }
            }).trigger("reloadGrid");

        });

        //绑定
        $('#btn-disable').click(function(e) {
            e.preventDefault();
            var ids = $("#grid").jqGrid('getGridParam', 'selarrrow').concat();
            if (!ids || ids.length == 0) {
                parent.Public.tips({
                    type: 1,
                    content: _(' 请先选择要绑定的！')
                });
                return;
            }
            handle.setStatuses(ids, true);
        });

        //解除绑定
        $('#btn-enable').click(function(e) {
            e.preventDefault();
            var ids = $("#grid").jqGrid('getGridParam', 'selarrrow').concat();
            if (!ids || ids.length == 0) {
                parent.Public.tips({
                    type: 1,
                    content: _(' 请先选择要解绑的！')
                });
                return;
            }
            handle.setStatuses(ids, false);
        });

        //刷新,可全局
        $('#btn-refresh').click(function(e) {
            e.preventDefault();
            $("#grid").trigger("reloadGrid")
        });
    }

    //var mod_PageConfig = Public.mod_PageConfig.init('customerList');//页面配置初始化
    initDom();
    initGrid();
    initEvent();
});