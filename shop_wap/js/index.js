$(function() {

    var headerClone = $('#header').clone();
    $(window).scroll(function(){
        if ($(window).scrollTop() <= $('#main-container1').height()) {
            headerClone = $('#header').clone();
            $('#header').remove();
            headerClone.addClass('transparent').removeClass('');
            headerClone.prependTo('.nctouch-home-top');
        } else {
            headerClone = $('#header').clone();
            $('#header').remove();
            headerClone.addClass('').removeClass('transparent');
            headerClone.prependTo('body');
        }
    });

    if(getCookie('sub_site_id') == '' || getCookie('sub_site_id') == 'undefined' || getCookie('sub_site_id') == null){
            loadScriptSubsite();
    }
    var sub_site_id = getCookie('sub_site_id');
    $.ajax({
        url: ApiUrl + "/index.php?ctl=Index&met=index&typ=json&ua=wap&sub_site_id="+sub_site_id,
        type: 'get',
        dataType: 'json',
        success: function(result) {
            var data = result.data;
            console.log(data);
            var html = '';
            if(typeof(data.subsite_is_open) == 'undefined' || !data.subsite_is_open){
//                $("#subsite_dev").hide();
                $('#cohesive_dev').hide();
            }else{
                if(typeof(data.sub_site_name) != 'undefined' && sub_site_id > 0){
                    $('.sub_site_name_span').html(data.sub_site_name);
                }else{
                    $('.sub_site_name_span').html('全部');
                }
            }
            $(".site_logo").attr('src',data.site_logo);

            $.each(data.module_data, function(k, v) {
                $.each(v, function(kk, vv) {
                    console.info(data.module_data[k][kk]);
                    if(data.module_data[k][kk].title){
                        var title = '';
                        for(var i=0;i<data.module_data[k][kk].title.length;i++){
                            if(i < data.module_data[k][kk].title.length-1){
                                title += data.module_data[k][kk].title[i]+'/';
                            }else{
                                title += data.module_data[k][kk].title[i];
                            }

                        }
                        data.module_data[k][kk].title = title;
                    }
                    switch (kk) {
                        case 'slider_list':
                        case 'home3':
                            $.each(vv.item, function(k3, v3) {
                                vv.item[k3].url = buildUrl(v3.type, v3.data);
                            });
                            break;

                        case 'home1':
                            vv.url = buildUrl(vv.type, vv.data);
                            break;

                        case 'home2':
                        case 'home4':
                            vv.square_url = buildUrl(vv.square_type, vv.square_data);
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);
                            break;
                    }
                    if (k == 0) {
                        $("#main-container1").html(template.render(kk, vv));
                    } else {
                        html += template.render(kk, vv);
                    }
                    return false;
                });

            });
            

            $("#main-container2").html(html);
            var mySwipers = new Swiper(".swiper-container-index", {
                autoplay:3000,
                pagination: '#pagination',
                paginationClickable: true,

            });
            var arr_color=["style1","style2","style3"];

            $.each($(".common-tit"),function(i,val){
                if(i>=3){
                     i=i-3*Math.floor(i/3);

                }
                $(this).addClass(arr_color[i]);


            })


        }
    });

$(window).scroll(function(){
    var index_banner_hg=$(".swiper-container-index").height();

    if($(document).scrollTop()>index_banner_hg){
        $(".head-fixed").addClass("scroll-bg");
    }else{
        $(".head-fixed").removeClass("scroll-bg");
    }
})

});

    function getUnreadMsgCount(){
        if(!getCookie('user_account')){
            return ;
        }
        $.ajax({
            type: "post", url: ImApiUrl + "/index.php?ctl=Api_Chatlog&met=getUnreadMsgCount&typ=json", data: {u: getCookie('user_account')}, dataType: "json", success: function (t)
            {
                var data = t.data;
                if(data.count > 0)
                {
                    $('.message').addClass('active');
                }else{
                    $('.message').removeClass('active');
                }
            }
        });
    }
    var unreadCount = self.setInterval("getUnreadMsgCount()",60000); //60秒一次

