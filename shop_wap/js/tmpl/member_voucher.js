var pagesize = 20;
var curpage = 1;

var k = getCookie("key");
var u = getCookie("id");

var state = getQueryString('act');
$(function ()
{
    if (!k || !u)
    {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return ; 
    }
    
    ajaxVoucher(state);
    
});

function ajaxVoucher(state){
    $.ajax({
        type: "get", url: ApiUrl + "/index.php?ctl=Buyer_Voucher&met=voucher&typ=json&state="+state+"&pagesize="+pagesize+"&curpage="+curpage, data: {k:k,u:u}, dataType: "json", success: function (e)
        {
            if (e.status == 200){
                if (!e.data.items){
                    return false;
                }else{
                    s = e.data;
                    if(e.data.items.length > 0){
                        var t = template.render("voucher_list", s);
                        $("#v_list").append(t);
                        curpage ++;
                    }
                }
            }else{
               return false; 
            }
        }
    });
}

$(window).scroll(function (){
    if ($(window).scrollTop() + $(window).height() > $(document).height() - 1){
        ajaxVoucher(state);
    }
});
