$(function ()
{
    var searchFlag = false;
    var filterClassCombo, userCombo;
    var handle = {
        statusFmatter: function (val, opt, row, oper)
        {
            var text = val == 1 ? '已启用' : '已禁用';
            var cls = val == 1 ? 'ui-label-success' : 'ui-label-default';
            return '<span class="set-status ui-label ' + cls + '" data-enable="' + val + '" data-id="' + row.id + '">' + text + '</span>';
        },

        //修改状态
        setStatus: function (id, is_enable)
        {
            if (!id)
            {
                return;
            }
            Public.ajaxPost(SITE_URL + '?ctl=Config&met=setPluginState&typ=json', {
                goods_id: id,
                enable: Number(is_enable)
            }, function (data)
            {
                if (data && data.status == 200)
                {
                    parent.Public.tips({content: '状态修改成功！'});
                    $('#grid').jqGrid('setCell', id, 'plugin_state', is_enable);
                }
                else
                {
                    parent.Public.tips({type: 1, content: '状态修改失败！' + data.msg});
                }
            });
        }
    };


    SYSTEM = system = parent.SYSTEM;

    function initDom()
    {
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

    function initGrid()
    {
        var grid_row = Public.setDialogGrid();
        console.info(grid_row);
        var colModel = [ {
            "name": "goods_id",
            "index": "goods_id",
            "label": "商品SKU",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 100
        },  {
            "name": "goods_price",
            "index": "goods_price",
            "label": "商品价格",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        },{
            "name": "goods_stock",
            "index": "goods_stock",
            "label": "商品库存",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 100
        },{
            "name": "goods_code",
            "index": "goods_code",
            "label": "商家编号货号",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }];
        //mod_PageConfig.gridReg('grid', colModel);
        //colModel = mod_PageConfig.conf.grids['grid'].colModel;


        $('#grid').jqGrid({
                data: goods_data.items,
                datatype: 'local',
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
                viewrecords: true,
                cmTemplate: {
                    sortable: false
                },
                rowNum: 100,
                rowList: [100, 200, 500],
                localReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "goods_id"},
                //scroll: 1,
                loadComplete: function (res)
                {
                    console.info(res);
                    var re_records = $("#grid").getGridParam('records');
                    if (re_records==0 || re_records==null)
                    {
                        $("#grid").parent().append("<div class=\"norecords\">没有符合数据</div>");

                        $(".norecords").show();
                    }
                    else
                    {
                        //如果存在记录，则隐藏提示信息。
                        $(".norecords").hide();
                    }
                },
                resizeStop: function (newwidth, index)
                {
                    //mod_PageConfig.setGridWidthByIndex(newwidth, index, 'grid');
                }
            }
        ).navGrid('#page', {
            edit: false,
            add: false,
            del: false,
            search: false,
            refresh: false
        });
    }

    function initEvent()
    {
        var match_con = $('#matchCon');
        //查询
        $('#search').on('click', function (e)
        {
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

        //设置状态
        $('#grid').on('click', '.set-status', function (e)
        {
            e.stopPropagation();
            e.preventDefault();

            var id = $(this).data('id'),
                is_enable = Number(!$(this).data('enable'));
            handle.setStatus(id, is_enable);
        });
        //导入
        $('#btn-import').on('click', function (e)
        {
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
})
;