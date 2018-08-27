
//im
if(IM_STATU == 1){

//            im_builder_ch();
        if(!getCookie('user_account') || getCookie('user_account') == 'undefined'){
            getUserAccount();
            $("#login_content").show();
            load_goodseval(SITE_URL  + '?ctl=Index&met=fastLogin','login_content');

        } else {
            $('#imbuiler').show();
//            $('#imbuiler')[0].contentWindow.bottom_bar();
            $('#imbuiler').contents().find('.bottom-bar a').click();
  
        }
        iconbtncomment();

    getUserAccount();
}
 
function getUserAccount(){
    $.ajax({
        type: "GET",
        url: SITE_URL + "?ctl=Index&met=getUserLoginInfo&typ=json",
        data: {},
        dataType: "json",
        success: function(data){ 
            if(data.data.status == 200 && typeof data.data.user_account != 'undefined'){
                var user_account_log = data.data.user_account;
                if(getCookie('user_account') == null || getCookie('user_account') != user_account_log){
                    setCookie('user_account',user_account_log,365); 
                }
                $('#imbuiler').show();
//                $('#imbuiler')[0].contentWindow.bottom_bar();
                $('#imbuiler').contents().find('.bottom-bar a').click();
            }

        }
    });
}
function iconbtncomment(){

    $('.icon-btncomment').click(function(){ 
        if(!getCookie('user_account') || getCookie('user_account') == 'undefined'){
            $("#login_content").show();
            load_goodseval(SITE_URL  + '?ctl=Index&met=fastLogin','login_content');

        }
        var ch_u = $('.chat-enter').attr('rel');
        if(ch_u == getCookie('user_account')){ 

             alert_box('不能跟自己聊天'); 
             return ;
        }
        var inner = $('#imbuiler')[0].contentWindow;
        $('#imbuiler').show();
        //查看聊天右侧的用户列表有没有，没有就点一下最下面的就出来了。
        var dis = $('#imbuiler').contents().find('.chat-list').css('display');

        if(dis!='block'){
            $('#imbuiler').contents().find('.bottom-bar a').click();     
        }  
        inner.chat(ch_u);
        $('#imbuiler')[0].contentWindow.bottom_bar();
        return false;
    });
}

//function im_builder_ch(){
//    var onl = $(".tbar-tab-online-contact");   
//    onl.show();
//
//    $(".tbar-tab-online-contact").click(function(){
//        if(!getCookie('user_account') || getCookie('user_account') == 'undefined'){
//            getUserAccount();
//            $("#login_content").show();
//            load_goodseval(SITE_URL  + '?ctl=Index&met=fastLogin','login_content');
//            return;
//        }
//        else
//        {
//            $('#imbuiler').show();
//            $('#imbuiler')[0].contentWindow.bottom_bar();
//            $('#imbuiler').contents().find('.bottom-bar a').click();
//            return;
//        }
//    });
//}