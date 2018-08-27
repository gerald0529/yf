$(function () {
    var id = getQueryString("id");
    $.ajax({
        url: ApiUrl + "/index.php?ctl=Points&met=detail&typ=json",
        data: {id: id},
        type: "get",
        success: function ( data ) {
            if ( data.status == 200 ) {
                data = data.data;
                $(".fixed-tab-pannel").html(data.goods_detail.points_goods_body);
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
        window.location.href = WapSiteUrl + "/tmpl/integral_product_detail.html?id=" + id
    });
});