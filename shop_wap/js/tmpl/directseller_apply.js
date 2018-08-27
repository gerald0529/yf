var page = pagesize;
var curpage = 1;
var firstRow = 0;
var hasMore = true;
var footer = false;
var reset = true;
var orderKey = "";

$(function () 
{
    var e = getCookie("key");
 
	if (!e) 
	{
        window.location.href = WapSiteUrl + "/tmpl/member/login.html"
    }
   
    if (getQueryString("data-state") != "") 
	{
        $("#filtrate_ul").find("li").has('a[data-state="' + getQueryString("data-state") + '"]').addClass("selected").siblings().removeClass("selected")
    }
    
	$("#search_btn").click(function () {
        reset = true;
        t()
    });
   
    $("#fixed_nav").waypoint(function () {
        $("#fixed_nav").toggleClass("fixed")
    }, {offset: "50"});
	
    function t() 
	{
        if (reset) 
		{
            curpage = 1;
            hasMore = true
        }
        $(".loading").remove();
       
  	    if (!hasMore) 
		{
            return false
        }
        
		hasMore = false;
        var t = $("#filtrate_ul").find(".selected").find("a").attr("data-state");
        var r = $("#order_key").val();

		$.ajax({
            type: "post",
            url: ApiUrl + "index.php?ctl=Distribution_Buyer_Directseller&met=index&typ=json&page=" + curpage+'&rows = '+page+'&firstRow='+firstRow,
            data: {k: e, u: getCookie('id'), status: t, orderkey: r},
            dataType: "json",
            success: function (e) 
			{
                checkLogin(e.login);
                curpage++;
 
			    if (!hasMore) {
                    get_footer()
                }
                
				if (e.data.items.length <= 0) {
                    $("#footer").addClass("posa")
                } else {
                    $("#footer").removeClass("posa")
                }
               
  			    var t = e;
                t.WapSiteUrl = WapSiteUrl;
                t.ApiUrl = ApiUrl;
                t.key = getCookie("key");
 
                template.helper("p2f", function (e) {
                    return (parseFloat(e) || 0).toFixed(2)
                });
                template.helper("parseInt", function (e) {
                    return parseInt(e)
                });
                var r = template.render("apply-list-tmpl", t);
                if (reset) {
                    reset = false;
                    $("#apply-list").html(r)
                } else {
                    $("#apply-list").append(r)
                }
				
				if(e.data.page < e.data.total)
				{
				   firstRow = e.data.page*page;
				   hasMore = true;
				}
				else
				{
				   hasMore = false;
				}
			}
		})
    }
 
    $("#filtrate_ul").find("a").click(function () {
        $("#filtrate_ul").find("li").removeClass("selected");
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        reset = true;
        window.scrollTo(0, 0);
        t()
    });
    t();
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            t()
        }
    })
});

function get_footer() {
    if (!footer) {
        footer = true;
        $.ajax({url: "../../js/tmpl/footer.js", dataType: "script"})
    }
}


function apply(shop_id)
{
	var e = getCookie("key");
	$.ajax({
        type: "post",
        url: ApiUrl + "index.php?ctl=Distribution_Buyer_Directseller&met=addDirectseller&typ=json&shop_id=" + shop_id,
        data: {k: e, u: getCookie('id')},
        dataType: "json",
        success: function (e) 
		{ 
			window.location.href = WapSiteUrl+"/tmpl/member/directseller_apply.html"
		}
	})
}