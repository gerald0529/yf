var stime={
    beginDate:0,
    startDate:0
};
function initDate() {
    var a = new Date,
        b = a.getFullYear(),
        c = ("0" + (a.getMonth() + 1)).slice(-2),
        d = ("0" + a.getDate()).slice(-2);
    stime.beginDate = b + "-" + c + "-01", stime.endDate = b + "-" + c + "-" + d;
    stime.startDate = b + "-" + c + "-" + d; //启用日期
}
$(function () {
    function a(){
        this.$_matchCon = $('#matchCon'),
            this.$_matchCon1 = $('#matchCon1'),
            this.$_beginDate = $('#beginDate').val(stime.beginDate),
            this.$_endDate = $('#endDate').val(stime.endDate),
            this.$_matchCon.placeholder(),
            this.$_matchCon1.placeholder(),
            this.$_beginDate.datepicker(),
            this.$_endDate.datepicker()
    }
    function b() {
        var a = Public.setGrid(),
            b = parent.SYSTEM.rights,
            c = !(parent.SYSTEM.isAdmin || b.AMOUNT_COSTAMOUNT),
            h = !(parent.SYSTEM.isAdmin || b.AMOUNT_INAMOUNT),
            k = !(parent.SYSTEM.isAdmin || b.AMOUNT_OUTAMOUNT),
            l = [
                
                {name: 'msg_sender',label: '发送者',width: 100,align: 'center'},
                {name: 'msg_receiver',label: '接收者',width: 120,align: 'center'},
                {name: 'date_created',label: '发送时间',width: 250,align: 'center'},
                {name: 'msg_content',label: '消息内容',width: 800,align: 'center'},
                {name: 'reply',label: '回复',width: 100,align: 'center'}
            ];
       
            $('#grid').jqGrid({
                 url:SITE_URL +'?ctl=User_Message&met=getList&typ=json&is_read=2&is_group=1',
                datatype: 'json',
                autowidth: true,
                shrinkToFit:true,
                forceFit:false,
                loadtext:"",
                loadui:false,
                width: a.w,
                height: a.h,
                altRows: !0,
                gridview: !0,
                onselectrow: !1,
                multiselect: !1,
                colModel: l,
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
                
                jsonReader: {
                    root: 'data.items',
                    records: 'data.records',
                    total: 'data.total',
                    repeatitems: !1,
                    id: 'msg_log_id'
                },
                loadComplete: function (a) {
                    if (a && 200 == a.status) {
                        var b = {
                        };
                        a = a.data;
                        for (var c = 0; c < a.items.length; c++) {
                            var d = a.items[c];
                            d['id'] = d.msg_log_id;
                            b[d.msg_log_id] = d
                        }
                        $('#grid').data('gridData', b)
                    }
                },
                loadError: function (a, b, c) {
                    parent.Public.tips({
                        type: 1,
                        content: '操作失败了哦，请检查您的网络链接！'
                    })
                },
                resizeStop: function (a, b) {
                    j.setGridWidthByIndex(a, b, 'grid')
                }
            }).navGrid('#page', {
                edit: !1,
                add: !1,
                del: !1,
                search: !1,
                refresh: !1
            }).navButtonAdd('#page', {
                caption: '',
                buttonicon: 'ui-icon-config',
                onClickButton: function () {
                    j.config()
                },
                position: 'last'
            })
    }
     
     
     
      b(),
        initDate()

        setInterval(function(){

            $("#grid").trigger("reloadGrid")

        },1000);
});

