$(function () {
    var e = getCookie("key");
    var u = getCookie("id");
    var from = getQueryString('from');
    
    if (!e) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    var a = getQueryString("order_id");
    var up_img = new Array();
    var i = 0;
    $.getJSON(ApiUrl + "/index.php?ctl=Buyer_Order&met=evaluation&act=add&typ=json", {
        k: e,
        u: u,
        order_id: a
    }, function (r) {
        if (r.status == 250) {
            $.sDialog({skin: "red", content: r.msg, okBtn: false, cancelBtn: false});
            return false
        }
        var l = template.render("member-evaluation-script", r.data);
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
                var $this = $(e),
                    order_goods_id = $this.data('order-goods-id'),
                    $picture = $('#picture_' + order_goods_id);
                if (a.state != 'SUCCESS') {
                    e.parent().siblings(".upload-loading").remove();
                    $.sDialog({skin: "red", content: "图片尺寸过大！", okBtn: false, cancelBtn: false});
                    return false
                }
                
                $picture.append('<div class="pic-thumb" id="' + i + '" style="display:inline-block;"><img style="width:50px;height:50px" src="' + a.url + '"/><input type="hidden" name="goods[' + order_goods_id + '][evaluate_image][' + i + ']" value="' + a.url + '" /><b class="icon-close"></b> </div>');
                e.parent().siblings(".upload-loading").remove();
                up_img.push(i);
                i++;
                
                if ($picture.find('img').length == 5) {
                    $picture.parent().find('.nctouch-upload').hide();
                }
            }
        });
        
        /*商品评价的小星星*/
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
        
        $(document).on('click', '.icon-close', function () {
            var id = $(this).parents().attr('id');
            for (var j in up_img) {
                if (id == up_img[j]) {
                    up_img.splice(j, 1);
                    $('#' + id).remove();
                }
            }
            if (up_img.length < 5) {
                $('.nctouch-upload').show();
            }
        });
        
        /*文本域键盘事件，将文本长度写入 统计中。页面控制最大输入长度为200*/
//      $("#content").keyup(function () {
//          var msg = $("#content").val();
//          $("#words").html(msg.length);
//      });
//      
         $(".js-content").on("input propertychange", function() {  
        var $this = $(this),  
            _val = $this.val(),  
            count = "";  
        if (_val.length > 200) {  
            $this.val(_val.substring(0, 200));  
        }  
        count = $this.val().length;  
         $(this).next().find(".js-words").text(count);  
    });  
        
        
        
        $(".btn-l").click(function () {
            var orderGoodsIds = [],
                evaluation = [],
                formArr = $("form").serializeArray(),
                formMap = {};
            
            $('input[name="order_goods_ids"]').each(function () {
                orderGoodsIds.push(this.value);
            });
            
            for (var i in formArr) {
                var name = formArr[i].name,
                    value = formArr[i].value;
                formMap[name] = value;
            }
            
            for (var i in orderGoodsIds) {
                var imageArr = [],
                    orderGoodsId = orderGoodsIds[i],
                    score = formMap['goods[' + orderGoodsId + '][score]'],
                    comment = formMap['goods[' + orderGoodsId + '][comment]'];
                
                $('#picture_' + orderGoodsId).find('img').each(function () {
                    imageArr.push(this.src);
                });
                
                evaluation.push([
                    orderGoodsId,
                    score,
                    'good',
                    comment,
                    imageArr.join(',')
                ]);
            }
            
            
            // for (var ts = 0; ts < order_goods_id_list.length; ts++) {
            //     var evaluation_little = new Array();
            //     var order_goods_id = order_goods_id_list[ts];
            //     evaluation_little.push(order_goods_id);                              //order_goods_id
            //     evaluation_little.push(l["goods[" + order_goods_id + "][score]"]);   //source
            //     evaluation_little.push('good');                                      //默认good 懒得算了
            //     evaluation_little.push(l["goods[" + order_goods_id + "][comment]"]); //comment
            //
            //     //计算imgurl
            //     var imgurl = new String();
            //     for (var tsi in up_img) {
            //
            //         imgurl += $('#' + tsi).find('input').val();
            //         imgurl += ',';
            //
            //     }
            //     evaluation_little.push(imgurl);                                       //url
            //     evaluation_little.push(l["goods[" + order_goods_id + "][anony]"]); //是否匿名
            //     evaluation.push(evaluation_little);
            // }
            
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?ctl=Goods_Evaluation&met=addGoodsEvaluation&typ=json",
                data: {evaluation: evaluation, k: getCookie('key'), u: getCookie('id')},
                dataType: "json",
                async: false,
                success: function (e) {
                    checkLogin(e.login);
                    console.info(evaluation);
                    if (e.status == 250) {
                        $.sDialog({skin: "red", content: '评价失败！', okBtn: false, cancelBtn: false});
                        return false
                    }
                    // 评价成功后即查看评价
                    if (from == 'chain') {
                        window.location.href = WapSiteUrl + "/tmpl/member/chain_order_list.html";
                    } else {
                        // window.location.href = WapSiteUrl + "/tmpl/member/order_list.html";
                        console.log(a);
                        window.location.href = WapSiteUrl + "/tmpl/member/member_evaluation_again.html?order_id=" + a;
                    }
                }
            })
        })
    })
});
