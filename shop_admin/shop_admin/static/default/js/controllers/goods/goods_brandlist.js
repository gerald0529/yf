function callback(a)
{
    var b = Public.getDefaultPage(),
        c = $('#grid').jqGrid('getGridParam', 'selrow');
    if (c && c.length > 0)
    {
        var d = $('#grid').jqGrid('getRowData', c);
        d.id = c;

        var e = d.brand_name,
            f = parent.$("#filter-brand");

        f.find('input').val(e),
            f.data('id', d),
        /*api.data.type && b.SYSTEM[api.data.type].push(d);
        var g = f.data('callback');
        'function' == typeof g && g(d)*/
        api.data.callback(c,e);
    }
}
var urlParam = Public.urlParam(),
    zTree,
    multiselect = urlParam.multiselect || !0,
    defaultPage = Public.getDefaultPage(),
    SYSTEM = defaultPage.SYSTEM,
    taxRequiredCheck = SYSTEM.taxRequiredCheck,
    taxRequiredInput = SYSTEM.taxRequiredInput,
    api = frameElement.api,
    data = api.data || {},
    queryConditions = {
        skey: api.data.skey || '',
        isDelete: data.isDelete || 0
    },
    addList = {}
THISPAGE = {
    init: function (a)
    {
        this.initDom(),
            this.loadGrid(),
            this.addEvent()
    },
    initDom: function ()
    {
        this.$_matchCon = $('#matchCon'),
            this.$_matchCon.placeholder(),
        queryConditions.skey && this.$_matchCon.val(queryConditions.skey);
    },
    loadGrid: function ()
    {
        var a = SITE_URL + '?ctl=Goods_Brand&met=listBrand&typ=json';
            var b = ([
                {
                    name: 'brand_name',
                    label: '品牌名称',
                    index: 'brand_name',
                    width: 100,
                    title: !1
                },
                {
                    name: 'brand_displayorder',
                    label: '排序',
                    index: 'brand_displayorder',
                    width: 100,
                    title: !1
                },
                {
                    name: 'brand_recommend',
                    label: '品牌推荐',
                    index: 'brand_recommend',
                    width: 100,
                    title: !1
                },
                {
                    name: 'brand_show_type',
                    label: '展现形式',
                    index: 'brand_show_type',
                    width: 100,
                    title: !1
                }
            ]);
            $('#grid').jqGrid({
                url: a,
                postData: queryConditions,
                datatype: 'json',
                autowidth: !0,
                height: 354,
                altRows: !0,
                gridview: !0,
                onselectrow: !1,
                multiselect: false,
                multiboxonly: false,
                colModel: b,
                pager: '#page',
                viewrecords: !0,
                cmTemplate: {
                    sortable: !1
                },
                rowNum: 100,
                rowList: [
                    100,
                    200,
                    500
                ],
                shrinkToFit: !0,
                jsonReader: {
                    root: 'data.rows',
                    records: 'data.records',
                    total: 'data.total',
                    repeatitems: !1,
                    id: 'id'
                },
                loadComplete: function (a)
                {
                    $('#jqgh_grid_cb').hide()
                },
                loadError: function (a, b, c)
                {
                },
                onSelectRow:function(rowid,status){
                    if(status){
                        var rowData = $('#grid').jqGrid('getRowData', rowid);
                        addList[rowid] = rowData;
                    }else if(addList[rowid]){
                        delete addList[rowid];
                    }
                },
                onSelectAll:function(aRowids,status){
                    for ( var i = 0, len = aRowids.length; i < len; i++){
                        var rowid = aRowids[i];
                        if(status){
                            var rowData = $('#grid').jqGrid('getRowData', rowid);
                            addList[rowid] = rowData;
                        }else if(addList[rowid]){
                            delete addList[rowid];
                        }
                    }
                }
            });
    },
    reloadData: function (a)
    {
        $('#grid').jqGrid('setGridParam', {
            page: 1,
            postData: a
        }).trigger('reloadGrid')
    },
    addEvent: function ()
    {
        var a = this;
        $('.grid-wrap').on('click', '.ui-icon-search', function (a)
        {
            a.preventDefault();
            var b = $(this).parent().data('id');
            Business.forSearch(b, '')
        }),
            $('#search').click(function ()
            {
                var b = '输入编号 / 名称 / 联系人 / 电话查询' === a.$_matchCon.val() ? '' : a.$_matchCon.val(),
                    c = a.catorageCombo.getValue();
                a.reloadData({
                    skey: b,
                    categoryId: c
                })
            }),
            $('#refresh').click(function ()
            {
                a.reloadData(queryConditions)
            })
    }
};
THISPAGE.init();
