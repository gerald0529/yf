var reset = true;
var curpage = 0;
var pagesize = 12;
var page = pagesize;
var hasMore = true;
var footer = false;

$(function() {
    var param = {
        k: getCookie("key"),
        u: getCookie("id")
    };


    if (getQueryString("data-state") != "") {
        $("#filtrate_ul").find("li").has('a[data-state="' + getQueryString("data-state") + '"]').addClass("selected").siblings().removeClass("selected")
    }


    function t(){

        var t = $("#filtrate_ul").find(".selected").find("a").attr("data-state");

        if (reset) {
            curpage = 0;
            hasMore = true
        }

        $.ajax({
            url: ApiUrl + "/index.php?ctl=Buyer_RedPacket&met=redPacket&typ=json&ua=wap&state="+t+'&firstRow='+curpage,
            type: 'get',
            dataType: 'json',
            data: param,
            success: function(result) {
                if (result.status == 200) {

                    curpage = result.data.page * pagesize;

                    if(page < result.data.totalsize)
                    {
                        hasMore = true;
                    }

                    if (!hasMore) {
                        get_footer()
                    }

                    $('#none').hide();

                    if (result.data.items.length <= 0) {
                        if(curpage == 12){
                            $('#none').show();
                        }
                        $("#footer").addClass("posa")
                    } else {
                        $("#footer").removeClass("posa")
                    }

                    var redpactetBaseHtml = template.render('repacket_base', result.data);
                    if(reset){
                        reset = false;
                        $("#buyer_goods_review").html(redpactetBaseHtml);

                    }else{
                        $("#buyer_goods_review").append(redpactetBaseHtml);

                    }

                }

            }
        });

    }

    t();

    $("#filtrate_ul").find("a").click(function () {
        $("#filtrate_ul").find("li").removeClass("selected");
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        window.scrollTo(0, 0);
        reset = true;
        t()
    });

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            if(hasMore){
                t()
            }
        }
    })

    function get_footer() {
        if (!footer) {
            footer = true;
            $.ajax({url: "../js/tmpl/footer.js", dataType: "script"})
        }
    }
});


