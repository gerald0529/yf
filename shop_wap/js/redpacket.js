var reset = true;
var curpage = 0;
var pagesize = 10;
var page = pagesize;
var hasMore = true;
var footer = false;

$(function() {
    var param = {
        k: getCookie("key"),
        u: getCookie("id")
    };


    function t(){

        var orderby = $("#filtrate_ul").find(".cur").attr("data-orderby");

        if (reset) {
            curpage = 0;
            hasMore = true
        }


        $.ajax({
            url: ApiUrl + "/index.php?ctl=RedPacket&met=redPacket&typ=json&ua=wap&orderby="+orderby+'&firstRow='+ curpage ,
            type: 'get',
            dataType: 'json',
            data: param,
            success: function(result) {
                if (result.status == 200) {

                    curpage = result.data.redpacket.page * pagesize;

                    if(page < result.data.redpacket.totalsize)
                    {
                        hasMore = true;
                    }

                    if (!hasMore) {
                        get_footer()
                    }


                    $('#none').hide();

                    if (result.data.redpacket.items.length <= 0) {
                        if(curpage == 10){
                            $('#none').show();
                        }
                        $("#footer").addClass("posa")
                    } else {
                        $("#footer").removeClass("posa")
                    }



                    var redpactetIndexHtml = template.render('repacket_index', result.data);
                    if(reset){
                        reset = false;
                        $("#repacket").html(redpactetIndexHtml);

                    }else{
                        $("#repacket").append(redpactetIndexHtml);

                    }

                }

            }
        });

    }

    t();

    $("#filtrate_ul").find("a").click(function () {
        $("#filtrate_ul").find("a").removeClass("cur");
        $(this).addClass("cur").siblings().removeClass("cur");
        $('#nav').html($(this).html());
        $(".packets-type").toggle();
        reset = true;
        window.scrollTo(0, 0);

        t()
    });


    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            if(hasMore){
                t()
            }
        }
    })


    $(document).on('click','#exchange .go-use',function(){
        var red_packet_t_id = $(this).attr("redpacket_t_id");
        var that = this;


        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=RedPacket&met=receiveRedPacket&typ=json",
            data: {red_packet_t_id: red_packet_t_id,k:param.k,u:param.u},
            dataType: "json",
            success: function (e) {
                if (e.status == 200) {

                    var num = parseInt($(that).parents().prev().attr('data-num'))+1;
                    $(that).parent().parent().find(".stat").attr('data-num',num).find('aa').html(num);
                    $.sDialog({skin: "green", content: "领取成功！", okBtn: false, cancelBtn: false});

                } else {
                    var msg = e.msg? e.msg+"！" : "领取失败！";
                    $.sDialog({skin: "red", content: msg, okBtn: false, cancelBtn: false})
                }
            }
        })
    });





});


