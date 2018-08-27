$(function() {
    var searchFlag = false;
    var filterClassCombo, catorageCombo;
    var handle = {
        //修改、新增
        operate: function(oper, row_id) {
            if (oper == 'add') {
                var title = _('新增');
                var data = {
                    oper: oper,
                    callback: this.callback
                };
            } else {
                var title = _('修改');

                var data = {
                    oper: oper,
                    rowId: row_id,
                    rowData: $("#grid").data('gridData')[row_id],
                    callback: this.callback
                };
            }

            $.dialog({
                title: title,
                content: 'url:' + SITE_URL + '?ctl=Base_Search&met=manage&typ=e',
                data: data,
                width: 400,
                height: 165,
                max: false,
                min: false,
                cache: false,
                lock: true,
                zIndex:1
            });
        },

        //删除
        del: function(row_ids) {
            $.dialog.confirm(_('删除的将不能恢复，请确认是否删除？'), function() {
                Public.ajaxPost(SITE_URL + '?ctl=Base_Search&met=remove&typ=json', {
                    search_id: row_ids
                }, function(data) {
                    if (data && data.status == 200) {
                        var id_arr = data.data.search_id || [];
                        if (row_ids.split(',').length === id_arr.length) {
                            parent.Public.tips({
                                content: sprintf(_('成功删除 %d 个！'), id_arr.length)
                            });
                        } else {
                            parent.Public.tips({
                                type: 2,
                                content: data.data.msg
                            });
                        }
                        for (var i = 0, len = id_arr.length; i < len; i++) {
                            $('#grid').jqGrid('setSelection', id_arr[i]);
                            $('#grid').jqGrid('delRowData', id_arr[i]);
                        };
                    } else {
                        parent.Public.tips({
                            type: 1,
                            content: _('删除失败！') + data.msg
                        });
                    }
                });
            });
        },
       
        callback: function(data, oper, dialogWin) {
            var gridData = $("#grid").data('gridData');
            if (!gridData) {
                gridData = {};
                $("#grid").data('gridData', gridData);
            }

            //计算期初余额字段difMoney
            //data.difMoney = data.amount - data.periodMoney;

            gridData[data.search_id] = data;

            if (oper == "edit") {
                $("#grid").jqGrid('setRowData', data.search_id, data);
                dialogWin && dialogWin.api.close();
            } else {
                $("#grid").jqGrid('addRowData', data.search_id, data, 'first');
                dialogWin && dialogWin.resetForm(data);
                window.location.reload();
            }
        },

        //操作项格式化，适用于有“修改、删除”操作的表格
        operFormatter: function(val, opt, row) {
            var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
            return html_con;
        },
       
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
            "name": "operate",
            "label": "操作",
            "width": 60,
            "sortable": false,
            "search": false,
            "resizable": false,
            "fixed": true,
            "align": "center",
            "title": false,
            "formatter": handle.operFormatter
        }, {
            "name": "search_keyword",
            "index": "search_keyword",
            "label": "搜索词",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "search_char_index",
            "index": "search_char_index",
            "label": "显示词",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "search_nums",
            "index": "search_nums",
            "label": "搜索次数",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }];
        //mod_PageConfig.gridReg('grid', colModel);
        //colModel = mod_PageConfig.conf.grids['grid'].colModel;
        $('#grid').jqGrid({
            url: SITE_URL + '?ctl=Base_Search&met=getSearchList&typ=json',
            datatype: 'json',
            autowidth: true,
            shrinkToFit: false,
            forceFit: true,
            width: grid_row.w,
            height: Public.setGrid().h,
            altRows: true,
            gridview: true,
            onselectrow: false,
            multiselect: false, //多选
            colModel: colModel,
            cmTemplate: {
                sortable: true
            },
            sortname: "search_nums", //指定默认排序的列
            sortorder: "desc", //指定默认排序方式
            pager: '#page',
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
                id: "search_id"
            },
            loadComplete: function(response) {
                if (response && response.status == 200) {
                    var gridData = {};
                    data = response.data;
                    for (var i = 0; i < data.items.length; i++) {
                        var item = data.items[i];
                        item['id'] = item.search_id;
                        gridData[item.search_id] = item;
                    }

                    $("#grid").data('gridData', gridData);
                } else {
                    var msg = response.status === 250 ? (searchFlag ? _('没有满足条件的结果哦！') : _('没有数据哦！')) : response.msg;
                    parent.Public.tips({
                        type: 2,
                        content: msg
                    });
                }

                var re_records = $("#grid").getGridParam('records');
                if (re_records == 0 || re_records == null) {
                    if ($("#grid").parent().find(".norecords").length < 1) {
                        $("#grid").parent().append(_('<div class="norecords">没有符合数据</div>'));
                    }


                    $(".norecords").show();
                } else {
                    //如果存在记录，则隐藏提示信息。
                    $(".norecords").hide();
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
        }).navButtonAdd('#page', {
            caption: "",
            buttonicon: "ui-icon-config",
            onClickButton: function() {
                //mod_PageConfig.config();
            },
            position: "last"
        });
    }

    function initEvent() {
       
        //新增
        $('#btn-add').on('click', function(e) {
            e.preventDefault();
            handle.operate('add');
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

        //修改
        $('#grid').on('click', '.operating .ui-icon-pencil', function(e) {
            e.preventDefault();
            //if (!Business.verifyRight('BU_UPDATE'))
            //{
            //    return;
            //}
            var id = $(this).parent().data('id');
            handle.operate('edit', id);
        });

        //删除
        $('#grid').on('click', '.operating .ui-icon-trash', function(e) {
            e.preventDefault();
            var id = $(this).parent().data('id');
            handle.del(id + '');
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