var page = pagesize;
var curpage = 1;
var hasMore = true;
var footer = false;
var reset = true;
var orderKey = "";
var firstRow = 0;
$(function () {

    var e = getCookie("key");
    if (!e) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html"
    }

    function t()
    {
        $(".loading").remove();
        if (!hasMore)
        {
            return false
        }
        hasMore = false;

        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Distribution_Buyer_Directseller&met=index&typ=json&firstRow="+firstRow,
            data: {k: e, u: getCookie('id')},
            dataType: "json",
            success: function (e) {
                checkLogin(e.login);
                curpage++;

                if (!hasMore) {
                    get_footer()
                }

                if (e.data.items.length <= 0)
                {
                    $("#footer").addClass("posa")
                } else {
                    $("#footer").removeClass("posa")
                }

                var t = e;
                template.helper("p2f", function (e) {
                    return (parseFloat(e) || 0).toFixed(2)
                });
                template.helper("parseInt", function (e) {
                    return parseInt(e)
                });

                console.log(t);

                var r = template.render("directseller-userapply-list-tmpl", t);
                $("#directseller-userapply-list").append(r)

                if(e.data.page < e.data.total)
                {
                    firstRow = e.data.page*page;
                    hasMore = true;
                }
                else
                {
                    hasMore = false;
                }
            }
        })
    }

    t();
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            t()
        }
    })



    $("#directseller-userapply-list").on('click','#apply',function(){
        $.post(ApiUrl  + '/index.php?ctl=Distribution_Buyer_Directseller&met=addDirectseller&typ=json',{k: e, u: getCookie('id')},function(data)
            {
                console.info(data);
                if(data && 200 == data.status)
                {
                    $.sDialog({
                        skin: "red",
                        content: "申请成功，等待审核",
                        okBtn: false,
                        cancelBtn: false
                    });
                    location.reload();
                    return false;
                }
                else
                {
                    $.sDialog({
                        skin: "red",
                        content: "申请失败，请重新申请",
                        okBtn: false,
                        cancelBtn: false
                    });
                    return false;
                }
            }
        );
    });


});

function get_footer() {
    if (!footer) {
        footer = true;
        $.ajax({url: "../../js/tmpl/footer.js", dataType: "script"})
    }
}

