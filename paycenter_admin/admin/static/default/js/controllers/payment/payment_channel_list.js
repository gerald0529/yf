$(function() {
    var searchFlag = false;
    var catorageCombo;
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
                content: 'url:' + SITE_URL + '?ctl=Payment_Channel&met=manage&typ=e',
                data: data,
                width: $(window).width() * 0.8,
                height: 400,
                max: false,
                min: false,
                cache: false,
                zIndex:900,
                lock: true
            });
        },

        //删除
        del: function(row_ids) {
            $.dialog.confirm(_('删除的将不能恢复，请确认是否删除？'), function() {
                Public.ajaxPost(SITE_URL + '?ctl=Payment_Channel&met=remove&typ=json', {
                    payment_channel_id: row_ids
                }, function(data) {
                    if (data && data.status == 200) {
                        var id_arr = data.data.payment_channel_id || [];
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
            Public.ajaxPost(SITE_URL + '?ctl=Payment_Channel&met=enable&typ=json', {
                payment_channel_id: id,
                payment_channel_id_enable: Number(is_enable)
            }, function(data) {
                if (data && data.status == 200) {
                    parent.Public.tips({
                        content: _('状态修改成功！')
                    });
                    $('#grid').jqGrid('setCell', id, 'payment_channel_id_enable', is_enable);
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
            Public.ajaxPost(SITE_URL + '?ctl=Payment_Channel&met=enable&typ=json', {
                payment_channel_id: sel_ids,
                payment_channel_id_enable: Number(is_enable)
            }, function(data) {
                if (data && data.status == 200) {
                    parent.Public.tips({
                        content: _('状态修改成功！')
                    });
                    for (var i = 0; i < ids.length; i++) {
                        var id = ids[i];
                        $('#grid').jqGrid('setCell', id, 'payment_channel_id_enable', is_enable);
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

            gridData[data.payment_channel_id] = data;

            if (oper == "edit") {
                $("#grid").jqGrid('setRowData', data.payment_channel_id, data);
                dialogWin && dialogWin.api.close();
            } else {
                $("#grid").jqGrid('addRowData', data.payment_channel_id, data, 'first');
                dialogWin && dialogWin.resetForm(data) && dialogWin.api.close();
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
            "name": "payment_channel_name",
            "index": "payment_channel_name",
            "label": "支付名称",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "payment_channel_config",
            "index": "payment_channel_config",
            "label": "支付接口配置信息",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,

            "formatter": function(val, opt, row){
                return JSON.stringify(val)

            },
            "width": 300
        }, {
            "name": "payment_channel_wechat",
            "index": "payment_channel_wechat",
            "label": "微信中是否可以使用",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 60,
            "formatter": function(val, opt, row) {
                var text = val == 0 ? _('已禁用') : _('已启用');
                var cls = val == 0 ? 'ui-label-default' : 'ui-label-success';
                return text;
            }
        }, {
            "name": "payment_channel_enable",
            "index": "payment_channel_enable",
            "label": "是否启用",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 60,
            "formatter": function(val, opt, row) {
                var text = val == 0 ? _('已禁用') : _('已启用');
                var cls = val == 0 ? 'ui-label-default' : 'ui-label-success';
                return text;
            }
        }];


        $('#grid').jqGrid({
            url: SITE_URL + '?ctl=Payment_Channel&met=lists&typ=json',
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
            sortname: "payment_channel_id", //指定默认排序的列
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
                id: "payment_channel_id"
            },
            loadComplete: function(response) {
                if (response && response.status == 200) {
                    var gridData = {};
                    data = response.data;
                    for (var i = 0; i < data.items.length; i++) {
                        var item = data.items[i];
                        item['id'] = item.payment_channel_id;
                        gridData[item.payment_channel_id] = item;
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
            }
        }).navGrid('#grid-pager', {
                edit: false,
                add: false,
                del: false,
                search: false,
                refresh: false
            }, {}, // edit options
            {}, // add options
            {}, // delete options
            {
                multipleSearch: true,
                multipleGroup: true,
                showQuery: true
            }
        ).navButtonAdd('#grid-pager', {
            caption: _("新增"),
            buttonicon: "ui-icon-plus",
            onClickButton: function() {
                handle.operate('add');
            },
            position: "last"
        }).navButtonAdd('#grid-pager', {
            caption: _("修改"),
            buttonicon: "ui-icon-pencil",
            onClickButton: function() {
                var id = $('#grid').jqGrid('getGridParam', 'selrow');

                if (id) {
                    handle.operate('edit', id);
                } else {
                    parent.Public.tips({
                        type: 2,
                        content: _('请选择需要修改的项')
                    })
                }
            },
            position: "last"
        }).navButtonAdd('#grid-pager', {
            caption: _("删除"),
            buttonicon: "ui-icon-trash",
            onClickButton: function() {

                var ids = $('#grid').jqGrid('getGridParam', 'selarrrow');
                ids.length ? handle.del(ids.join()) : (function() {
                    var id = $('#grid').jqGrid('getGridParam', 'selrow');

                    if (id) {
                        handle.del(id + '');
                    } else {
                        parent.Public.tips({
                            type: 2,
                            content: _('请选择需要删除的项')
                        })
                    }

                })();


            },
            position: "last"
        }).navButtonAdd('#grid-pager', {
            caption: _("搜索"),
            buttonicon: "ui-icon-search",
            onClickButton: function() {
                alert("Deleting Row");
            },
            position: "last"
        }).navButtonAdd('#grid-pager', {
            caption: _("刷新"),
            buttonicon: "ui-icon-refresh",
            onClickButton: function() {
                $("#grid").trigger("reloadGrid");
            },
            position: "last"
        }).navButtonAdd('#grid-pager', {
            caption: _("设置"),
            buttonicon: "ui-icon-gear",
            onClickButton: function() {
            },
            position: "last"
        });
    }

    function initEvent() {
        var match_con = $('#matchCon');
        //查询
        $('#search').on('click', function(e) {
            e.preventDefault();
            var skey = $.trim(match_con.val());
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
                content: 'url:' + SITE_URL + '?ctl=Payment_Channel&met=import&typ=e',
                lock: true
            });
        });

        //修改
        $('#grid').on('click', '.operating .ui-icon-pencil:not(.ui-icon-disabled)', function(e) {
            e.preventDefault();
            var id = $(this).parent().data('id');
            handle.operate('edit', id);
        });

        //删除
        $('#grid').on('click', '.operating .ui-icon-trash:not(.ui-icon-disabled)', function(e) {
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
                is_enable = Number(!$(this).data('enable'));

            alert(id);
            alert(is_enable);
            handle.setStatus(id, is_enable);
        });
    }


    initDom();
    initGrid();
    initEvent();
});



