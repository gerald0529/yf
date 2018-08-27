<?php
include __DIR__.'/../../includes/header.php';
?>

<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="Author" contect="U2FsdGVkX1+liZRYkVWAWC6HsmKNJKZKIr5plAJdZUSg1A==">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-touch-fullscreen" content="yes" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="msapplication-tap-highlight" content="no" />
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
	<title>我的推广</title>
	<link rel="stylesheet" type="text/css" href="../../css/base.css">
	<link rel="stylesheet" type="text/css" href="../../css/new-style.css">
	<link rel="stylesheet" type="text/css" href="../../css/private-store.css">
</head>
<style>
	body{background: #fff;}
	.w132{width: 3rem;}
	.w144{width: 3.272rem}
	.w108{width: 2.454rem;}
	.w262{width: 5.954rem;}
	.message{font-size:0.681rem;margin-right: 0.545rem;}
	.head-ser a.header-inps{background: #f3f5f7;}
	.search-z-ipt{background: #f3f5f7;color: #666;}

	:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
    color: #666; opacity:1; 
}

::-moz-placeholder { /* Mozilla Firefox 19+ */
    color: #666;opacity:1;
}

input:-ms-input-placeholder{
    color: #666;opacity:1;
}

input::-webkit-input-placeholder{
    color: #666;opacity:1;
}
</style>
<body>
	<header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="member.html"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>我的推广</h1>
            </div>
        </div>
    </header>
    
    <!--详情-->
    <div class="mt20 search-z">
    <div class="head-ser">
        <div class="cohesive " id="cohesive_dev" style="display: none;">
            <a href="./tmpl/changecity.html" class="colf">
                <span class="city-text sub_site_name_span"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                    全部                </font></font></span>
                <i class="icon-drapdown"></i>
            </a>
        </div>
        <a  class="header-inps">
            <i class="icon"></i>
            <input type="text" value="" placeholder="请输入会员名称" class="search-z-ipt userName"/>
        </a>

		<div id="header-nav" class="message search_btn" >
			搜索
		</div>
    </div>

</div>
    <ul class="" id="user_list">
    </ul>

	<script type="text/html" id="user_list_tmpl">
		<% var list = data.data; %>
		<% if (list.length > 0){%>
            <% for(var i = 0;i < list.length;i++){
        
            var info = list[i];
            %>
		<li class="extension">
			<div class="extension-name mt0">
				<img src="<%=info.user_logo%>"/>
				<span><%=info.user_name%></span>
			</div>
			<div class="clearfix">
				<ul class="fl mr-40">
					<li class="clearfix">
						<p class="fl w132 tr">手机号:</p>
						<p class="fl"><%=info.user_mobile%></p>
					</li>
					<li class="clearfix">
						<p class="fl w132 tr">推广会员数:</p>
						<p class="fl"><%=info.fx_user_count%>人</p>
					</li>
					<li class="clearfix">
						<p class="fl w132 tr">带来佣金:</p>
						<p class="fl">￥<%=info.commission%></p>
					</li>
				</ul>
				<ul class="fl ">
					<li class="clearfix">
						<p class="fl tr w108">性别:</p>
						<p class="fl"><% if(info.user_sex == 0){ %>女<% } else if(info.user_sex == 1){ %>男<% } else { %>保密<% } %></p>
					</li>
					<li class="clearfix">
						<p class="fl tr w108">消费总额:</p>
						<p class="fl">￥<%=info.expends%></p>
					</li>
					<li class="clearfix">
						<p class=" fl tr w108">注册时间:</p>
						<p class="fl"><%=info.user_regtime%></p>
					</li>
				</ul>
			</div>
		</li>
		<%}%>
		<% if (hasmore) {%>
		<li class="loading">
			<div class="spinner"><i></i></div>数据读取中...</li>
		<% } %>
		<%}else{%>
		<div class="nctouch-norecord order">
			<div class="norecord-ico"><i></i></div>
			<dl>
				<dt>暂无任何推广用户</dt>
			</dl>
		</div>
		<%}%>

	</script>

	<script type="text/javascript" src="../../js/zepto.js"></script>
	<script type="text/javascript" src="../../js/common.js"></script>
	<script type="text/javascript" src="../../js/template.js"></script>
	<script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script>
        var page = pagesize;
        var curpage = 1;
        var hasMore = true;
        var footer = false;
        var reset = true;
        var orderKey = "";
        var firstRow = 0;
        $(function () {
            var e = getCookie("key");
            if (!e) {
                window.location.href = WapSiteUrl + "/tmpl/member/login.html"
            }
            function t()
            {
                $(".loading").remove();
                if (!hasMore)
                {
                    return false
                }
                hasMore = false;

                $.ajax({
                    type: "post",
                    url: ApiUrl + "/index.php?ctl=Fenxiao&met=FenxiaoList&typ=json&firstRow="+firstRow,
                    data: {k: e, u: getCookie('id'),userName:$(".userName").val()},
                    dataType: "json",
                    success: function (e) {
                        checkLogin(e.login);
                        curpage++;

                        if (!hasMore) {
                            get_footer()
                        }

                        if (e.data.data.length <= 0)
                        {
                            $("#footer").addClass("posa")
                        } else {
                            $("#footer").removeClass("posa")
                        }

                        var t = e;
                        template.helper("p2f", function (e) {
                            return (parseFloat(e) || 0).toFixed(2)
                        });
                        template.helper("parseInt", function (e) {
                            return parseInt(e)
                        });

                        var r = template.render("user_list_tmpl", t);
                        $("#user_list").append(r)

                        if(e.data.page < e.data.pages)
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

            t();
            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
                    t()
                }
            })


            $(".head-ser").find(".search_btn").click(function () {
                reset = true;
                window.scrollTo(0, 0);
                curpage = 1;
                firstRow = 0;
                hasMore = true;
                $("#user_list").html('');
                t()
            });
        });

        function get_footer() {
            if (!footer) {
                footer = true;
                $.ajax({url: "../../js/tmpl/footer.js", dataType: "script"})
            }
        }





    </script>
</body>
</html>
<?php
include __DIR__.'/../../includes/footer.php';
?>