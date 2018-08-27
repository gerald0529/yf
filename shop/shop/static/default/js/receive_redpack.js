/**
 * Created by yesai on 2016/5/13.
 */
function initField() {
    if (rowData.voucher_price_id) {
        $("#voucher_price").val(rowData.voucher_price);
        $("#voucher_price_describe").val(rowData.voucher_price_describe);
        $("#voucher_defaultpoints").val(rowData.voucher_defaultpoints);
    }
}

function initPopBtns() {
    var t = ["兑换", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function () {
            postData(red_packet_t_id, site_url);
            return false;
        }
    }, {id: "cancel", name: t[1]})
}

function postData(e, url) {
    $.post(url + "?ctl=RedPacket&typ=json&met=receiveRedPacket", {red_packet_t_id: e}, function (data) {
        var t = data.status;
        if (200 == t) {
            callback && "function" == typeof callback && callback(data.data, t, window, data.msg)
        } else {
            callback && "function" == typeof callback && callback(data.data, t, window, data.msg)
        }
    });
}

api = frameElement.api, red_packet_t_id = api.data.vid || {}, site_url = api.data.url, callback = api.data.callback;
initPopBtns();
//initField();