$(function () {
    var id = getQueryString("id");
    $.ajax({
        url: ApiUrl + "/index.php?ctl=GroupBuy&met=detail&typ=json",
        data: {id: id},
        type: "get",
        success: function ( data ) {
            if ( data.status == 200 ) {
                data = data.data;
                $(".fixed-tab-pannel").html(data.groupbuy_detail.groupbuy_intro);
            } else {
                $.sDialog({
                    skin:"red",
                    content:data.msg,
                    okBtn:false,
                    cancelBtn:false
                });
            }

        }
    });
    $('body').on('click', '#productDetail', function(){
        window.location.href = WapSiteUrl + "/tmpl/group_buy_goods.html?groupbuy_id=" + id
    });
});