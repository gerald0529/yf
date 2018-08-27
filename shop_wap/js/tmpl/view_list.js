var key = getCookie("key");

$(function () {
    if (!key) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return false;
    }
    
    var e = new ncScrollLoad;
    e.loadInit({
        url: ApiUrl + "/index.php?ctl=Buyer_Favorites&met=footprintwap&typ=json",
        getparam: {k: key, u: getCookie('id')},
        tmplid: "viewlist_data",
        containerobj: $("#viewlist"),
        iIntervalId: true,
        data: {WapSiteUrl: WapSiteUrl}
    });
    
    $("#clearbtn").click(function () {
        $.sDialog({
            autoTime: 2000, //当没有 确定和取消按钮的时候，弹出框自动关闭的时间
            skin: "red",
            content: "确定清空吗？",
            okBtn: true,
            cancelBtn: true,
            "okBtnText": "确定", //确定按钮的文字
            "cancelBtnText": "取消", //取消按钮的文字
            "lock": true, //是否显示遮罩
            okFn: function () {
                $.ajax({
                    type: "post",
                    url: ApiUrl + "/index.php?ctl=Buyer_Favorites&met=delFootPrint&typ=json",
                    data: {k: key, u: getCookie('id')},
                    dataType: "json",
                    async: false,
                    success: function (e) {
                        if (e.status == 200) {
                            window.setTimeout(function () {
                                location.href = WapSiteUrl + "/tmpl/member/views_list.html";
                            }, 100);
                        } else {
                            $.sDialog({skin: "red", content: e.data.error, okBtn: false, cancelBtn: false});
                        }
                    }
                });
            }
        });
    });
    
});
