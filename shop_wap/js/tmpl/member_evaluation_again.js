$(function () {
    var e = getCookie("key");
    var u = getCookie("id");
    var from = getQueryString('from');
    if (!e) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    var a = getQueryString("order_id");
    $.getJSON(ApiUrl + "/index.php?ctl=Buyer_Order&met=getEvaluationByOrderId&typ=json", {k: e,u:u, order_id: a}, function (r) {
        console.info(r.data);
        if (r.status == 250) {
            $.sDialog({skin: "red", content: r.msg, okBtn: false, cancelBtn: false});
            return false
        }
        var l = template.render("member-evaluation-script", {'data':r.data,'from':from});
        $("#member-evaluation-div").html(l);
        $('input[name="upfile"]').ajaxUploadImage({
            url: ApiUrl + "/index.php?ctl=Upload&action=uploadImage",
            data: {key: e},
            start: function (e) {
                e.parent().after('<div class="upload-loading"><i></i></div>');
                e.parent().siblings(".pic-thumb").remove()
            },
            success: function (e, a) {
                checkLogin(a.login);
                if (a.state != 'SUCCESS') {
                    e.parent().siblings(".upload-loading").remove();
                    $.sDialog({skin: "red", content: "图片尺寸过大！", okBtn: false, cancelBtn: false});
                    return false
                }
                e.parent().after('<div class="pic-thumb"><img src="' + a.url + '"/></div>');
                e.parent().siblings(".upload-loading").remove();
                e.parents("a").next().val(a.url)
            }
        });

        /*评分给小星星*/
        $(".star-level").find("i").click(function () {
            var e = $(this).index();
            for (var a = 0; a < data[i][j].scores; a++) {
                // var r = $(this).parent().find("i").eq(a);
                // if (a <= e) {
                //     r.removeClass("star-level-hollow").addClass("star-level-solid")
                // } else {
                //     r.removeClass("star-level-solid").addClass("star-level-hollow")
                // }
            }
            $(this).parent().next().val(e + 1)
        });

        /*追加评价*/
        $(".btn-l").click(function () {
            var a = $(this).parent().find('.evaluation_goods_id').val();
            if(from == 'chain')
            {
                window.location.href = WapSiteUrl + "/tmpl/member/member_evaluation_again_add.html?from=chain&oge_id="+a;
            }
            else
            {
                window.location.href = WapSiteUrl + "/tmpl/member/member_evaluation_again_add.html?oge_id="+a;
            }

        })
    })
});
