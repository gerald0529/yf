$(function() {
    var searchFlag = false;
    var filterClassCombo, userCombo;
    var handle = {
        //修改、新增
        operate: function (oper, row_id)
        {
            if (oper == 'download')
            {
                var title = _('下载证书');
                var data = {
                    oper: oper,
                    callback: this.callback
                };
                var met = 'manage';

                alert($("#grid").data('gridData')[row_id]['licence_key']);
                return;
            }
            else if(oper == 'add')
            {
                var title = _('新增');
                var data = {
                    oper: oper,
                    parent_id: row_id,
                    callback: this.callback
                };
                var met = 'manage';
            }
            else
            {
                var title = sprintf(_('修改 [%s]'), $("#grid").data('gridData')[row_id]['licence_log_id']);
                var data = {
                    oper: oper,
                    rowId: row_id,
                    rowData: $("#grid").data('gridData')[row_id],
                    callback: this.callback
                };
                console.info($("#grid").data('gridData')[row_id]);

                var met = 'manage';
            }

            $.dialog({
                title: title,
                content: 'url:' + SITE_URL + '?ctl=Licence&met=' + met + '&typ=e',
                data: data,
                width: 600,
                height: 400,
                max: false,
                min: false,
                cache: false,
                lock: true
            });
        },

        callback: function (data, oper, dialogWin)
        {
            var gridData = $("#grid").data('gridData');
            if (!gridData)
            {
                gridData = {};
                $("#grid").data('gridData', gridData);
            }

            gridData[data.id] = data;

            if (oper == "edit" || oper == "close" || oper == "verify")
            {
                $("#grid").jqGrid('setRowData', data.id, data);

                dialogWin && dialogWin.api.close();
            }
            else
            {
                $("#grid").jqGrid('addRowData', data.id, data, 'first');
                dialogWin && dialogWin.api.close();
            }
        },

        //操作项格式化，适用于有“修改、删除”操作的表格
        operFmatter: function (val, opt, row)
        {
            var nav_str = '';
            var add_str = '';

            var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span>' + nav_str + '<span class="ui-icon ui-icon-circle-arrow-s" title="下载证书"></span>' + add_str + '</div>';

            return html_con;
        }
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

        /*
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
        */
    };

    function initGrid() {
        var grid_row = Public.setGrid();
        var colModel = [{
            "name": "licence_log_id",
            hidden : true,
            "index": "licence_log_id",
            "label": "授权Id",
            "classes": "ui-ellipsis",
            "align": "left",
            hidden : true,
            "title": false,
            "fixed": true,
            "width": 60
        }, {
            "name": "licence_log_domain",
            "index": "licence_log_domain",
            "label": "域名",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 220
        },{
            "name": "licence_key",
            "index": "licence_key",
            "label": "Key",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 600
        },  {
            "name": "app_id",
            "index": "app_id",
            "label": "APP Id",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 190
        },  {
            "name": "licence_log_date",
            "index": "licence_log_date",
            "label": "检测日期",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
             "width": 100
        },  {
            "name": "licence_log_state",
            "index": "licence_log_state",
            "label": "状态",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
             "width": 90
        }];
        //mod_PageLicence.gridReg('grid', colModel);
        //colModel = mod_PageLicence.conf.grids['grid'].colModel;
        $('#grid').jqGrid({
            url: SITE_URL + '?ctl=Licence&met=logLists&typ=json',
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
                id: "licence_log_id"
            },
            loadComplete: function(response) {
                if (response && response.status == 200) {
                    var gridData = {};
                    data = response.data;
                    for (var i = 0; i < data.items.length; i++) {
                        var item = data.items[i];
                        item['id'] = item.licence_log_id;
                        gridData[item.licence_log_id] = item;
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
                //mod_PageLicence.setGridWidthByIndex(newwidth, index, 'grid');
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


        //新增
        $('#btn-add').on('click', function (e)
        {
            e.preventDefault();
            handle.operate('add');
        });

        $('#grid').on('click', '.operating .ui-icon-circle-arrow-s', function (e)
        {
            e.preventDefault();
            var id = $(this).parent().data('id');
            handle.operate('download', id);
        });
        //
        $('#grid').on('click', '.operating .ui-icon-pencil', function (e)
        {
            e.preventDefault();
            //if (!Business.verifyRight('BU_UPDATE'))
            //{
            //    return;
            //}
            var id = $(this).parent().data('id');
            handle.operate('edit', id);
        });

    }

    //var mod_PageLicence = Public.mod_PageLicence.init('customerList');//页面配置初始化
    initDom();
    initGrid();
    initEvent();
});