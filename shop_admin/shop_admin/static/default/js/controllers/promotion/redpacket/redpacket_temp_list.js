/**
 * Created by yesai
 */
$(function(){
	$source = $("#source").combo({
			data: [{
				id: "0",
				name: "红包状态"
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
            redpacket_t_title:''
        },
        hiddenAmount = false,
        SYSTEM = system = parent.SYSTEM;

    var handle = {
        operate: function (t, e)
        {
            if ("add" == t) //新增红包模板
            {
                var i = "平台红包 - 新增红包模板", a = {oper: t, callback: this.callback};
                $.dialog({
                    title: i,
                    content: "url:"+SITE_URL + '?ctl=Promotion_RedPacket&met=redPacketManage&typ=e',
                    data: a,
                    width:906,
                    height:$(window).height(),
                    max: !1,
                    min: !1,
                    cache: !1,
                    lock: !0
                })
            }
            else if("edit" == t) //编辑红包信息
            {
                var i = "平台红包 - 编辑红包模板", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
                $.dialog({
                    title: i,
                    content: "url:"+SITE_URL + '?ctl=Promotion_RedPacket&met=editRedPacketTemp&typ=e&id=' + e,
                    data: a,
                    width:685,
                    height:$(window).height(),
                    max: !1,
                    min: !1,
                    cache: !1,
                    lock: !0
                })
            }
            else if("detail" == t)   //查看红包详情
            {
                var i = "平台红包 - 红包列表";
                $.dialog({
                    title: i,
                    content: "url:"+SITE_URL + '?ctl=Promotion_RedPacket&met=redPacketInfo&typ=e',
                    data: {id: e},
                    width: $(window).width() * 0.6,
                    height: $(window).height(),
                    max: !1,
                    min: !1,
                    cache: !1,
                    lock: !0
                })
            }
        },
        callback: function (t, e, i)
        {
            var a = $("#grid").data("gridData");
            if (!a)
            {
                a = {};
                $("#grid").data("gridData", a)
            }
            a[t.redpacket_t_id] = t;
            if ("edit" == e)
            {
                $("#grid").jqGrid("setRowData", t.redpacket_t_id, t);
                i && i.api.close()
            }
            else
            {
                $("#grid").jqGrid("addRowData", t.member_id, t, "first");
                i && i.api.close()
            }
        },
        del: function (t)
        {
            $.dialog.confirm("删除的红包模板将不能恢复，请确认是否删除？", function ()
            {
                Public.ajaxPost(SITE_URL + '?ctl=Promotion_RedPacket&met=removeRedPacketTemp&typ=json', {redpacket_t_id: t}, function (e)
                {
                    if (e && 200 == e.status)
                    {
                        parent.Public.tips({content: "删除成功！"});
                        $("#grid").jqGrid("delRowData", t)
                    }
                    else
                    {
                        parent.Public.tips({type: 1, content: "删除失败！" + e.msg})
                    }
                })
            })
        },
        statusFormatter: function(val, opt, row) {
            var text = val == 0 ? _('否') : _('是');
            var cls = val == 0 ? 'ui-label-default' : 'ui-label-success';
            return '<span class="set-status ui-label ' + cls + '" data-enable="' + val + '" data-id="' + row.id + '">' + text + '</span>';
        },

        //修改状态
        setStatus: function(id, is_rec) {
            if (!id) {
                return;
            }
            Public.ajaxPost(SITE_URL + '?ctl=Promotion_RedPacket&met=enable&typ=json', {
                redpacket_t_id: id,
                is_rec: Number(is_rec)
            }, function(data) {
                if (data && data.status == 200) {
                    parent.Public.tips({
                        content: _('状态修改成功！')
                    });
                    $('#grid').jqGrid('setCell', id, 'redpacket_t_recommend', is_rec);
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
            this.$_redpacket_t_title = $('#redpacket_t_title');
            this.$_shop_name = $('#shop_name');
            this.$_redpacket_t_title.placeholder();
            this.$_shop_name.placeholder();
        },
        loadGrid: function(){
            var gridWH = Public.setGrid(), _self = this;
            var colModel = [
                {name:'operating', label:'操作', "classes": "ui-ellipsis",width:80, fixed:true, formatter:operFmatter, align:"center"},
                {name:'redpacket_t_title', label:'红包名称',"classes": "ui-ellipsis", width:200, align:"center"},
                {name:'redpacket_t_img', label:'红包图片', width:80, align:"center",formatter:online_imgFmt,classes:"redpacket_t_img"},
                {name:'redpacket_t_price', label:'面额（元）',"classes": "ui-ellipsis", width:100,align:'center'},
                {name:'redpacket_t_orderlimit', label:'消费限额（元）',"classes": "ui-ellipsis", width:100, align:"center"},
                {name:'redpacket_t_user_grade_label', label:'会员级别',"classes": "ui-ellipsis", width:100, align:"center"},
                {name:'redpacket_t_start_date', label:'开始时间',"classes": "ui-ellipsis",  width:150, align:"center"},
                {name:'redpacket_t_end_date', label:'结束时间',"classes": "ui-ellipsis",  width:150, align:"center"},
                {name:'redpacket_t_add_date', label:'最后修改时间',"classes": "ui-ellipsis",  width:150, align:"center"},
                {name:'redpacket_t_access_method_label', label:'领取方式',"classes": "ui-ellipsis",  width:100, align:"center"},
                {name:'redpacket_t_state_label', label:'状态',"classes": "ui-ellipsis",  width:100, align:"center"},
                {name:'redpacket_t_recommend', label:'推荐',"classes": "ui-ellipsis",  width:100, align:"center","formatter": handle.statusFormatter}
            ];
            $("#grid").jqGrid({
                url: SITE_URL + '?ctl=Promotion_RedPacket&met=getRedPacketTempList&typ=json',
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
                    id: "redpacket_t_id"
                },
                loadComplete: function (t)
                {
                    if (t && 200 == t.status)
                    {
                        var e = {};
                        t = t.data;
                        for (var i = 0; i < t.items.length; i++)
                        {
                            var a = t.items[i];
                            e[a.redpacket_t_id] = a;
                        }
                        $("#grid").data("gridData", e);

                        0 == t.items.length && parent.Public.tips({type: 2, content: "没有类型数据！"})
                    }
                    else
                    {
                        parent.Public.tips({type: 2, content: "获取类型数据失败！" + t.msg})
                    }
                },
                loadError : function(xhr,st,err) {
                    parent.Public.tips({
                        type: 1,
                        content: '操作失败了哦，请检查您的网络链接！'
                    });
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
                        THISPAGE.mod_PageConfig.config();
                    },
                    position:"last"
                });


            function operFmatter (val, opt, row) {
                var html_con = '';
                if(row.redpacket_t_giveout > 0) //已经有兑换的红包不允许删除
                {
                    html_con = '<div class="operating" data-id="' + row.redpacket_t_id + '"><span class="ui-icon ui-icon-disabled ui-icon-trash" data-dis="1" title="删除"></span><span class="ui-icon ui-icon-search" title="红包详情"></span><span class="ui-icon ui-icon-pencil" title="编辑"></span></div>';
                }
                else
                {
                    html_con = '<div class="operating" data-id="' + row.redpacket_t_id + '"><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-search" title="红包详情"></span><span class="ui-icon ui-icon-pencil" title="编辑"></span></div>';
                }
                return html_con;
            };

            function online_imgFmt(val, opt, row){
                if(val)
                {
                    val = '<img src="'+val+'" height=70>';
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
            //搜索
            $('#search').click(function(){
				queryConditions.redpacket_t_state = $source.getValue();
                queryConditions.redpacket_t_title = _self.$_redpacket_t_title.val();
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

            //添加
            $("#btn-add").click(function (t)
            {
                t.preventDefault();
                handle.operate("add");
            });
            //删除
            $(".grid-wrap").on("click", ".ui-icon-trash", function (e)
            {
                if($(this).attr('data-dis'))
                {
                    return false;
                }
                else
                {
                    e.preventDefault();
                    var e = $(this).parent().data("id");
                    handle.del(e)
                }
            });
            //编辑
            $('.grid-wrap').on('click', '.ui-icon-pencil', function(e){
                e.preventDefault();
                var e = $(this).parent().data("id");
                handle.operate("edit", e)
            });
            //查看
            $(".grid-wrap").on("click", ".ui-icon-search", function (e)
            {
                e.preventDefault();
                var e = $(this).parent().data("id");
                handle.operate("detail",e)
            });
            //刷新
            $("#btn-refresh").click(function ()
            {
                THISPAGE.reloadData('');
                _self.$_redpacket_t_title.val('');
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
