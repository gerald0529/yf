/**
 * Created by tech23 on 2016/11/8.
 */
$(function () {
    //显示要兑换的代金券信息
    $("[op_type='exchangebtn']").on('click', function () {
        var data_str = $(this).attr('data-param');
        eval("data_str = " + data_str);
        var a = {vid: data_str.vid, callback: recallback, url: SITE_URL};
        $.dialog({
            title: "您要兑换的平台红包信息",
            content: 'url: ' + SITE_URL + '?ctl=RedPacket&met=getRedPacketById&typ=e&id=' + data_str.vid,
            data: a,
            width: 500,
            height: 140,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        });
    });
    
    function recallback(t, e, i, msg) {
        if (200 == e) {
            parent.Public.tips({content: msg});
            i && i.api.close()
        } else {
            parent.Public.tips({content: msg});
            i && i.api.close()
        }
        var $voucher_t_box = $(".ncp-voucher-list").find('[data-id="' + t.voucher_t_id + '"]');
        $voucher_t_box.find(".point .giveout").html(t.voucher_t_giveout);
    }
});
