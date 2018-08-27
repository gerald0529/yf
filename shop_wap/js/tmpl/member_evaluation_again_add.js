$(function () {
    var e = getCookie("key");
    var u = getCookie("id");
    var from = getQueryString('from');
    if (!e) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    var a = getQueryString("oge_id");
    $.getJSON(ApiUrl + "/index.php?ctl=Buyer_Order&met=evaluation&act=again&typ=json", {k: e,u:u, oge_id: a}, function (r) {
        console.info(r);
        if (r.status == 250) {
            $.sDialog({skin: "red", content: r.msg, okBtn: false, cancelBtn: false});
            return false
        }
        var l = template.render("member-evaluation-script", r);
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

        /*追加给小星星*/
        $(".star-level").find("i").click(function () {
            var e = $(this).index();
            for (var a = 0; a < 5; a++) {
                var r = $(this).parent().find("i").eq(a);
                if (a <= e) {
                    r.removeClass("star-level-hollow").addClass("star-level-solid")
                } else {
                    r.removeClass("star-level-solid").addClass("star-level-hollow")
                }
            }
            $(this).parent().next().val(e + 1)
        });

        /*评论字数控制在200字数以内*/
        $("#content").keyup(function (){
            var lengths = $(this).val().length;
            if (lengths>199) {
                $(this).val($(this).val().substring(0,200));
            }
            if (lengths<=0) {
                lengths = 0;
            }
            $("#words").text(lengths);
        });

        /*追加提交*/
        $(".btn-l").click(function () {

            var evaluation_goods_id = $('#evaluation_goods_id').val();
            var order_goods_id = $('#order_goods_id').val();
            var content = $("#content").val();
            //计算imgurl
            var imgurl = new String();
            for (var tsi = 0; tsi < 4; tsi++)
            {
                image = $(".evaluate_image_" + tsi).val();
                if(image)
                {
                    imgurl += image + ",";
                }


            }
            var evaluate_img = imgurl;

            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?ctl=Goods_Evaluation&met=againGoodsEvaluation&typ=json",
                data: { evaluation_goods_id: evaluation_goods_id,content:content,evaluate_img:evaluate_img,order_goods_id:order_goods_id, k:getCookie('key'), u: getCookie('id')},
                dataType: "json",
                async: false,
                success: function (e) {
                    console.info(e);
                    checkLogin(e.login);
                    if (e.status == 250) {
                        $.sDialog({skin: "red", content: '追加评价失败', okBtn: false, cancelBtn: false});
                        return false
                    }
                    if(from == 'chain')
                    {
                        window.location.href = WapSiteUrl + "/tmpl/member/chain_order_list.html"
                    }
                    else
                    {
                        window.location.href = WapSiteUrl + "/tmpl/member/order_list.html"
                    }
                }
            })
        })
    })
});
