var k = getCookie("key");
var u = getCookie("id");

var shop_id = getQueryString("shop_id");

$(function () {
    if (shop_id > 0) {
        getVoucher(shop_id);
    }
    
    
});

function getVoucher(shop_id) {
    $.ajax({
        type: "get", url: ApiUrl + "/index.php?ctl=Shop&met=getShopVoucher&typ=json", data: {k: k, u: u, shop_id: shop_id}, dataType: "json", success: function (e) {
            if (e.status == 200) {
                if (!e.data.items) {
                    return false;
                } else {
                    if (e.data.items.length > 0) {
                        s = e.data;
                        var t = template.render("voucher_list", s);
                        $("#v_list").append(t);
                    }
                    
                }
            } else {
                return false;
            }
        }
    });
}

//领取代金券
function confrimVoucher(vid, point, price) {
    if (point > 0) {
        $.sDialog({
            content: "您确认花费" + point + "积分兑换" + price + "元代金券吗？",
            okBtn: true,
            cancelBtn: true,
            okBtnText: "确定",
            cancelBtnText: "取消",
            okFn: function () {
                receiveVoucher(vid);
            },
            cancelFn: function () {
                return;
            }
        });
    } else {
        receiveVoucher(vid);
    }
}


function receiveVoucher(vid) {
    var k = getCookie("key");
    var u = getCookie("id");
    $.ajax({
        type: "get", url: ApiUrl + "/index.php?ctl=Voucher&met=receiveVoucher&typ=json", data: {k: k, u: u, vid: vid}, dataType: "json", success: function (e) {
            if (e.status == 200) {
                $.sDialog({skin: "red", content: "成功领取代金劵！", okBtn: false, cancelBtn: false});
                window.location.reload();
            } else {
                var content = typeof(e.msg) != "undefined" ? e.msg : "领取失败！";
                $.sDialog({
                    content: content,
                    okBtn: false,
                    cancelBtn: false
                });
                return false;
            }
            
        }
    });
}
