<?php 
include __DIR__.'/../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>更换城市</title>
	<link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/index.css" />
    <link rel="stylesheet" href="../css/Group.css" />
    <link rel="stylesheet" href="../css/swiper.min.css" />
    <link rel="stylesheet" href="../css/nctouch_common.css" />
    <link rel="stylesheet" href="../css/nctouch_products_list.css" />
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/config.js"></script>
    <script type="text/javascript" src="../js/zepto.min.js"></script> 
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/swipe.js"></script>
    
    <style>
    	.header-r a i.more {
		    background-size: 40%;
		}
    </style>
    <script type="text/javascript">
        $(function() {
            getSubsiteList();
        });
        
        function getSubsiteList(){
            $.ajax({
                url: ApiUrl + "/index.php?ctl=Base_District&met=subSite&typ=json",
                type: 'get',
                dataType: 'json',
                success: function(result) {
                    var data = result.data.items;
                    var html = '';
                    $(".city-names").append("<a onclick=\"setsubsite(0,0)\">全部</a>");
                    $.each(data, function(k, v) {
                        $(".city-names").append("<a onclick=\"setsubsite("+v.subsite_id+",'"+v.sub_site_domain+"')\">"+v.sub_site_name+"</a>");
                    });
                }
            });
        }
        
        function setsubsite(subsite_id,domain){
            if(typeof(domain) == 'undefined' || !domain){
                addCookie('sub_site_id',subsite_id,0);
                window.location.href = WapSiteUrl+'/index.html?sub_site_id='+subsite_id;
            }else{
                var WapSiteUrlArray =WapSiteUrl.split( "/" ); 
                var WapSiteHost = WapSiteUrlArray[2];
                var WapSiteHostArray = WapSiteHost.split( "." );
                
                if(WapSiteHostArray[0] == 'www'){
                    WapSiteHost = WapSiteHost.replace('www.','');
                }
                window.location.href = 'http://'+domain+'.'+WapSiteHost+'/index.html?sub_site_id='+subsite_id;
            }
            
           
        }
    </script>
</head>
<body>
	<header id="header" class="nctouch-product-header fixed">
	    <div class="header-wrap">
	        <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>
	        <div class="city-title">选择城市</div>
	        <div class="header-r"><a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup style="display: inline;"></sup></a> </div>
	    </div>
	    <div class="nctouch-nav-layout">
	        <div class="nctouch-nav-menu"> <span class="arrow"></span>
	            <ul>
	                <li><a href="../index.html"><i class="home"></i>首页</a></li>
	                <li><a href="../tmpl/cart_list.html"><i class="cart"></i>购物车<sup style="display: inline;"></sup></a></li>
	                <li><a href="../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
	                <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
	            </ul>
	        </div>
	    </div>
	</header>
	<div class="nctouch-main-layout">
		<div class="city-names">

		</div>
	</div>
</body>


</html>
<?php 
include __DIR__.'/../includes/footer.php';
?>