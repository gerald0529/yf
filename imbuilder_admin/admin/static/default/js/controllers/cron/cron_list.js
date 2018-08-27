$(function() {
    var searchFlag = false;
    var filterClassCombo, catorageCombo;
    var handle = {
        //修改、新增
        operate: function(oper, row_id) {
            if (oper == 'add') {
                var title = _('新增计划任务');
                var data = {
                    oper: oper,
                    callback: this.callback
                };
            } else {
                var title = _('修改计划任务');

                var data = {
                    oper: oper,
                    rowId: row_id,
                    rowData: $("#grid").data('gridData')[row_id],
                    callback: this.callback
                };
            }

            $.dialog({
                title: title,
                content: 'url:' + SITE_URL + '?ctl=Base_Cron&met=manage&typ=e',
                data: data,
                width: $(window).width() * 0.4,
                height: $(window).height() * 0.4,
                max: false,
                min: false,
                cache: false,
                lock: true
            });
        },

        //删除
        del: function(row_ids) {
            $.dialog.confirm(_('删除的将不能恢复，请确认是否删除？'), function() {
                Public.ajaxPost(SITE_URL + '?ctl=Base_Cron&met=remove&typ=json', {
                    cron_id: row_ids
                }, function(data) {
                    if (data && data.status == 200) {
                        var id_arr = data.data.cron_id || [];
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
        //修改状态
        setStatus: function(id, is_enable) {
            if (!id) {
                return;
            }
            Public.ajaxPost(SITE_URL + '?ctl=Base_Cron&met=enable&typ=json', {
                cron_id: id,
                cron_active: Number(is_enable)
            }, function(data) {
                if (data && data.status == 200) {
                    parent.Public.tips({
                        content: _('状态修改成功！')
                    });
                    $('#grid').jqGrid('setCell', id, 'cron_active', is_enable);
                } else {
                    parent.Public.tips({
                        type: 1,
                        content: _('状态修改失败！') + data.msg
                    });
                }
            });
        },
        //批量修改状态
        setStatuses: function(ids, is_enable) {
            if (!ids || ids.length == 0) {
                return;
            }
            var arr_ids = $('#grid').jqGrid('getGridParam', 'selarrrow')
            var sel_ids = arr_ids.join();
            Public.ajaxPost(SITE_URL + '?ctl=Base_Cron&met=enable&typ=json', {
                cron_id: sel_ids,
                cron_active: Number(is_enable)
            }, function(data) {
                if (data && data.status == 200) {
                    parent.Public.tips({
                        content: _('状态修改成功！')
                    });
                    for (var i = 0; i < ids.length; i++) {
                        var id = ids[i];
                        $('#grid').jqGrid('setCell', id, 'enable', is_enable);
                    }
                } else {
                    parent.Public.tips({
                        type: 1,
                        content: _('状态修改失败！') + data.msg
                    });
                }
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

            gridData[data.cron_id] = data;

            if (oper == "edit") {
                $("#grid").jqGrid('setRowData', data.cron_id, data);
                dialogWin && dialogWin.api.close();
            } else {
                $("#grid").jqGrid('addRowData', data.cron_id, data, 'first');
                dialogWin && dialogWin.api.close();
            }
        },

        //操作项格式化，适用于有“修改、删除”操作的表格
        operFormatter: function(val, opt, row) {
            var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
            return html_con;
        },

        statusFormatter: function(val, opt, row) {
            var text = val == 0 ? _('已禁用') : _('已启用');
            var cls = val == 0 ? 'ui-label-default' : 'ui-label-success';
            return '<span class="set-status ui-label ' + cls + '" data-enable="' + val + '" data-id="' + row.id + '">' + text + '</span>';
        },
              dateFormatter: function(val, opt, row) {
            return $.DateTime.date('Y-m-d H:i:s', val);
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
            "name": "operate",
            "label": "操作",
            "width": 80,
            "sortable": false,
            "search": false,
            "resizable": false,
            "fixed": true,
            "align": "center",
            "title": false,
            "formatter": handle.operFormatter
        }, {
            "name": "cron_id",
            "index": "cron_id",
            "label": "任务id",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 60
        }, {
            "name": "cron_name",
            "index": "cron_name",
            "label": "任务名称",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 200
        }, {
            "name": "cron_script",
            "index": "cron_script",
            "label": "任务脚本",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 200
        }, {
            "name": "cron_lasttransact",
            "index": "cron_lasttransact",
            "label": "上次执行时间",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 150,
            "formatter": handle.dateFormatter
        }, {
            "name": "cron_nexttransact",
            "index": "cron_nexttransact",
            "label": "下一次执行时间",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 150,
            "formatter": handle.dateFormatter
        }, {
            "name": "cron_minute",
            "index": "cron_minute",
            "label": "分",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "cron_hour",
            "index": "cron_hour",
            "label": "小时",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "cron_day",
            "index": "cron_day",
            "label": "日",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "cron_month",
            "index": "cron_month",
            "label": "月",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "cron_week",
            "index": "cron_week",
            "label": "周",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "cron_active",
            "index": "cron_active",
            "label": "其是启用",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 60,
            "formatter": handle.statusFormatter
        }];
        //mod_PageConfig.gridReg('grid', colModel);
        //colModel = mod_PageConfig.conf.grids['grid'].colModel;
        $('#grid').jqGrid({
            url: SITE_URL + '?ctl=Base_Cron&met=lists&typ=json',
            datatype: 'json',
            autowidth: true,
            shrinkToFit: true,
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
            var skey = match_con.val() === '输入客户编号/ 名称/ 联系人/ 电话查询' ? '' : $.trim(match_con.val());
            var category_id = catorageCombo ? catorageCombo.getValue() : -1;
            $("#grid").jqGrid('setGridParam', {
                page: 1,
                postData: {
                    skey: skey,
                    category_id: category_id
                }
            }).trigger("reloadGrid");

        });

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

        //批量删除
        $('#btn-batchDel').click(function(e) {
            e.preventDefault();

            var ids = $('#grid').jqGrid('getGridParam', 'selarrrow');
            ids.length ? handle.del(ids.join()) : parent.Public.tips({
                type: 2,
                content: _('请选择需要删除的项')
            });
        });

        //禁用
        $('#btn-disable').click(function(e) {
            e.preventDefault();
            var ids = $("#grid").jqGrid('getGridParam', 'selarrrow').concat();
            if (!ids || ids.length == 0) {
                parent.Public.tips({
                    type: 1,
                    content: _(' 请先选择要禁用的！')
                });
                return;
            }
            handle.setStatuses(ids, true);
        });

        //启用
        $('#btn-enable').click(function(e) {
            e.preventDefault();
            var ids = $("#grid").jqGrid('getGridParam', 'selarrrow').concat();
            if (!ids || ids.length == 0) {
                parent.Public.tips({
                    type: 1,
                    content: _(' 请先选择要启用的！')
                });
                return;
            }
            handle.setStatuses(ids, false);
        });

        //设置状态
        $('#grid').on('click', '.set-status', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var id = $(this).data('id'),
                is_enable =  Number(!$(this).data('enable'));
            handle.setStatus(id, is_enable);
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