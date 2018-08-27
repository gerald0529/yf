var pagesize = pagesize;
var curpage = 1;
var hasMore = true;
var footer = false;
var reset = true;
$(function () {
    var e = getCookie("key");
    if (!e) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html"
    }
    getlist();
    $(window).scroll(function ()
    {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1)
        {
            getlist();
        }
    });

})   
function getlist(){
	if(!hasMore){
		return false;
	}
    $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Seller_Supplier_DistLog&met=index&typ=json&pagesize=" + pagesize + "&curpage=" + curpage,
            data: {k: getCookie("key"), u: getCookie('id'),path:'wap'},
            dataType: "json",
            success: function (e) {
            	console.info(e)
            	curpage++;
            	var r = template.render("distlog", e);
            	
            	if (reset) {
                    reset = false;
                    $("#distlog_list").html(r)
                } else {
                    $("#distlog_list").append(r)
                }
                
                if(e.data.page < e.data.total){
                	hasMore = true;
                }else{
                	hasMore = false;
                }
            }
    })   
}   