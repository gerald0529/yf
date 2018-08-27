



$(function (){

    var key = getCookie('key');

    if(!key) {
        var callback = WapSiteUrl + '/tmpl/member/member.html';

        var login_url   = UCenterApiUrl + '?ctl=Login&met=index&typ=e';

        var callback = ApiUrl + '?ctl=Login&met=check&typ=e&redirect=' + encodeURIComponent(callback);

        var login_url = login_url + '&from=wap&callback=' + encodeURIComponent(callback);

        return window.location.href = login_url;;
    }

    function LoadEvent()
    {
        $("#cart-list-wp").on("click", ".goods-del", function(){
            var $this = $(this),
                points_cart_id = $this.attr("cart_id");

            $.sDialog({
                skin:"red",
                content:'确认删除吗？',
                okBtn:true,
                cancelBtn:true,
                okFn: function() {
                    removePointsCart(points_cart_id, $this);
                }
            });
        });

        //购买数量 加/减

        $("#cart-list-wp").on("click", ".minus, .add", function(){

            var li = $(this).parents(".cart-litemw-cnt"),
                points_cart_id = li.attr('cart_id'),
                quantity = parseInt($(this).parent().find(".buynum").val());

            if ( $(this).hasClass("add") ) {
                quantity += 1;
            } else {
                quantity -= 1;
            }

            if ( quantity <= 0 ) return;
            editPointsCart(points_cart_id, quantity, this);
        });

        $("#cart-list-wp").on("click", "input[name='cart_id']", function(){
            calculatePoints();
        });

        $("#cart-list-wp").on("click", "input.all_checkbox", function(){
            if ( this.checked ) {
                $("input[name='cart_id']").prop("checked", "checked");
            } else {
                $("input[name='cart_id']").prop("checked", "");
            }

            calculatePoints();
        });

        $("#cart-list-wp").on("click", ".check-out", function(){

            if ( $(this).hasClass("ok") ) {
                var result = calculatePoints(),
                    sumPoints = result.sumPoints,
                    point_cart_id = result.point_cart_id,
                    param = "isIntegral=true&sumPoints=" + sumPoints + "&point_cart_id=" + encodeURI(point_cart_id);

                window.location.href = WapSiteUrl + "/tmpl/order/integral_product_buy.html?ifcart=1&" + param;
            }
        });
    }

    function initCartList(){

        $.ajax({
            url: ApiUrl + "/index.php?ctl=Points&met=pointsCart&typ=json",
            type: "GET",
            dataType: "json",
            data: { k: key, u: getCookie('id') },
            success: function ( data ) {
                if ( data.status == 200 ) {

                    var data = data.data, cart_list;

                    data.WapSiteUrl = WapSiteUrl;
                    cart_list = template.render("cart-list", data);

                    $("#cart-list-wp").append(cart_list);
                } else {
                    $.sDialog({skin: "red", content: data.msg, okBtn: false, cancelBtn: false});
                }
            }
        });
    }

    function editPointsCart ( points_cart_id, quantity, _this ) {

        var param = {
            k: key,
            u: getCookie('id'),
            points_cart_id: points_cart_id,
            quantity: quantity
        };

        $.ajax({
            url: ApiUrl + "/index.php?ctl=Points&met=editPointsCart&typ=json",
            type: "POST",
            dataType: "json",
            data: param,
            success: function ( data ) {
                if ( data.status == 200 ) {
                    var data = data.data;
                    $(_this).parent().find(".buynum").val(data.quantity), calculatePoints();
                } else {
                    $.sDialog({skin: "red", content: data.msg, okBtn: false, cancelBtn: false});
                }
            }
        });
    }

    function removePointsCart ( points_cart_id, _this ) {
        var param = {
            k: key,
            u: getCookie('id'),
            id: points_cart_id,
        };
        $.ajax({
            url: ApiUrl + "/index.php?ctl=Points&met=removePointsCart&typ=json",
            type: "POST",
            dataType: "json",
            data: param,
            success: function ( data ) {
                if ( data.status == 200 ) {
                    _this.parents("ul.nctouch-cart-item").remove();
                } else {
                    $.sDialog({skin: "red", content: data.msg, okBtn: false, cancelBtn: false});
                }
            }
        });
    }

    function calculatePoints() {
        var sumPoints = 0,
            point_cart_id = new Array();
        $("input[name='cart_id']:checked").each( function (i, e) {

            var point, quantity;

            point = $(e).parents("li").find(".buynum").val();
            quantity = $(e).parents("li").find(".buynum").data("point");

            sumPoints += point * quantity;

            point_cart_id.push($(this).parents("li").attr("cart_id"));
        });

        if ( $("input[name='cart_id']").length == $("input[name='cart_id']:checked").length ) {
            $("input.all_checkbox").prop("checked", "checked");
        } else {
            $("input.all_checkbox").prop("checked", "");
        }


        if ( sumPoints > 0 ) {
            $(".check-out").addClass("ok");
        } else {
            $(".check-out").removeClass("ok");
        }

        $(".nctouch-cart-bottom").find("em").text( sumPoints + "积分" );

        return { sumPoints: sumPoints, point_cart_id: point_cart_id };
    }


    initCartList();
    LoadEvent();

});