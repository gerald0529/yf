/**
 * Created by Administrator on 2016/5/15.
 */
$(function(){
	$source = $("#source").combo({
			data: [{
				id: "0",
				name: "代金券状态"
			},{
				id: "1",
				name: "有效"
			},{
				id: "2",
				name: "失效"
			}
			],
			value: "id",
			text: "name",
			width: 110
		}).getCombo();
		
    var queryConditions = {
            voucher_t_title:'',
            shop_name:''
        },
        hiddenAmount = false,
        SYSTEM = system = parent.SYSTEM;

    var handle = {
        operate: function (t, e)
        {
            var i = '编辑代金券';
            a = {oper: t, rowData: $("#grid").jqGrid('getRowData',e), callback: this.callback};

            $.dialog({
                title: i,
                content: "url:"+SITE_URL + '?ctl=Promotion_Voucher&met=getVoucherTempInfo&id=' + e,
                data: a,
                width:715,
                height:$(window).height(),
                max: !1,
                min: !1,
                cache: !1,
                lock: !0
            })
        },
        callback: function (t, e, i)
        {
            var a = $("#grid").data("gridData");
            if (!a)
            {
                a = {};
                $("#grid").data("gridData", a)
            }
            a[t.voucher_t_id] = t;
            if ("edit" == e)
            {
                $("#grid").jqGrid("setRowData", t.voucher_t_id, t);
                i && i.api.close()
            }
            else
            {
                $("#grid").jqGrid("addRowData", t.member_id, t, "last");
                i && i.api.close()
            }
        },
        statusFormatter: function(val, opt, row) {
            var text = val == 0 ? _('否') : _('是');
            var cls = val == 0 ? 'ui-label-default' : 'ui-label-success';
            return '<span class="set-status ui-label ' + cls + '" data-enable="' + val + '" data-id="' + row.id + '">' + text + '</span>';
        },

        linkShopFormatter: function(val, opt, row) {
            return '<a href="javascript:void(0)"><span class="to-shop" data-id="' + row.shop_id + '">' + val + '</span></a>';
        },

        //修改状态
        setStatus: function(id, is_rec) {
            if (!id) {
                return;
            }
            Public.ajaxPost(SITE_URL + '?ctl=Promotion_Voucher&met=enable&typ=json', {
                voucher_t_id: id,
                is_rec: Number(is_rec)
            }, function(data) {
                if (data && data.status == 200) {
                    parent.Public.tips({
                        content: _('状态修改成功！')
                    });
                    $('#grid').jqGrid('setCell', id, 'voucher_t_recommend', is_rec);
                } else {
                    parent.Public.tips({
                        type: 1,
                        content: _('状态修改失败！') + data.msg
                    });
                }
            });
        }
    };
    var THISPAGE = {
        initDom: function(){
            var defaultPage = Public.getDefaultPage();
            defaultPage.SYSTEM = defaultPage.SYSTEM || {};
            this.$_voucher_t_title = $('#voucher_t_title');
            this.$_shop_name = $('#shop_name');
            this.$_voucher_t_title.placeholder();
            this.$_shop_name.placeholder();
        },
        loadGrid: function(){
            var gridWH = Public.setGrid(), _self = this;
            var colModel = [
                {name:'operating', label:'操作', "classes": "ui-ellipsis",width:40, fixed:true, formatter:operFmatter, align:"center"},
                {name:'voucher_t_title', label:'代金券名称',"classes": "ui-ellipsis", width:200, align:"center"},
                {name:'shop_name', label:'店铺名称',"classes": "ui-ellipsis", width:150, align:"center","formatter": handle.linkShopFormatter},
                {name:'voucher_t_cat_name', label:'代金券分类',  width:150, align:"center"},
                {name:'voucher_t_price', label:'面额',"classes": "ui-ellipsis", width:100,align:'center'},
                {name:'voucher_t_limit', label:'消费金额',"classes": "ui-ellipsis", width:100, align:"center"},
                /*{name:'voucher_t_price', label:'会员级别', width:200,align:"center"},*/
                {name:'voucher_t_add_date', label:'添加时间',"classes": "ui-ellipsis",  width:150, align:"center"},
                {name:'voucher_t_start_date', label:'开始时间',"classes": "ui-ellipsis",  width:150, align:"center"},
                {name:'voucher_t_end_date', label:'结束时间',"classes": "ui-ellipsis",  width:150, align:"center"},
                {name:'voucher_t_access_method_label', label:'领取方式',"classes": "ui-ellipsis",  width:90, align:"center"},
                {name:'voucher_t_state_label', label:'状态',"classes": "ui-ellipsis",  width:100, align:"center"},
                {name:'voucher_t_recommend', label:'推荐',"classes": "ui-ellipsis",  width:100, align:"center","formatter": handle.statusFormatter}
            ];
            $("#grid").jqGrid({
                url: SITE_URL + '?ctl=Promotion_Voucher&met=getVoucherTempList&typ=json',
                postData: queryConditions,
                datatype: "json",
                autowidth: true,//如果为ture时，则当表格在首次被创建时会根据父元素比例重新调整表格宽度。如果父元素宽度改变，为了使表格宽度能够自动调整则需要实现函数：setGridWidth
                height: gridWH.h,
                altRows: true, //设置隔行显示
                gridview: true,
                multiboxonly: true,
                colModel:colModel,
                cmTemplate: {sortable: false, title: false},
                page: 1,
                sortname: 'number',
                sortorder: "desc",
                pager: "#page",
                rowNum: 100,
                rowList:[100,200,500],
                viewrecords: true,
                shrinkToFit: false,
                forceFit: true,
                jsonReader: {
                    root: "data.items",
                    records: "data.records",
                    repeatitems : false,
                    total : "data.total",
                    id: "voucher_t_id"
                },
                loadError : function(xhr,st,err) {

                },
                resizeStop: function(newwidth, index){
                    THISPAGE.mod_PageConfig.setGridWidthByIndex(newwidth, index, 'grid');
                }
            }).navGrid('#page',{
                edit:false,
                add:false,
                del:false,
                search:false,
                refresh:false}).navButtonAdd('#page',
                {
                    caption:"",
                    buttonicon:"ui-icon-config",
                    onClickButton: function(){
                        //THISPAGE.mod_PageConfig.config();
                    },
                    position:"last"
                });


            function operFmatter (val, opt, row) {
                var html_con = '<div class="operating" data-id="' + row.voucher_t_id + '"><span class="ui-icon ui-icon-pencil" title="编辑"></span></div>';
                return html_con;
            };

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
            //编辑
            $('.grid-wrap').on('click', '.ui-icon-pencil', function(e){
                e.preventDefault();
                var e = $(this).parent().data("id");
                handle.operate("edit", e)
            });

            //搜索
            $('#search').click(function(){
				queryConditions.voucher_t_state = $source.getValue();
                queryConditions.voucher_t_title = _self.$_voucher_t_title.val();
                queryConditions.shop_name = _self.$_shop_name.val();
                THISPAGE.reloadData(queryConditions);
            });

            //设置状态
            $('#grid').on('click', '.set-status', function(e) {
                e.stopPropagation();
                e.preventDefault();

                var id = $(this).data('id'),
                    is_rec = !$(this).data('enable');
                handle.setStatus(id, is_rec);
            });

            //跳转到店铺认证信息页面
            $('#grid').on('click', '.to-shop', function(e) {
                e.stopPropagation();
                e.preventDefault();
                var shop_id = $(this).attr('data-id');
                $.dialog({
                    title: '查看店铺信息',
                    content: "url:"+SITE_URL + '?ctl=Shop_Manage&met=getShoplist&shop_id=' + shop_id,
                    width: 1000,
                    height: $(window).height(),
                    max: !1,
                    min: !1,
                    cache: !1,
                    lock: !0
                })

            });

            //刷新
            $("#btn-refresh").click(function ()
            {
                THISPAGE.reloadData('');
                _self.$_voucher_t_title.val('');
                _self.$_shop_name.val('');
            });

            $(window).resize(function(){
                Public.resizeGrid();
            });
        }
    };

    THISPAGE.initDom();
    THISPAGE.loadGrid();
    THISPAGE.addEvent();
});
