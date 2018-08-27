<?php 
include __DIR__.'/../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>拼团中心</title>
	<link rel="stylesheet" href="../css/base.css">
	<link rel="stylesheet" href="../css/swiper.min.css">
	<link rel="stylesheet" href="../css/fight-groups.css">
	<script src="../js/swiper.min.js"></script>
    <script type="text/javascript" src="../js/zepto.min.js"></script>
</head>
<body>
	<header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l">
                <a href="../index.html"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>拼团中心</h1>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
    	<div class="pt_nav bgf swiper-container">
    	 	<div class="swiper-wrapper" id="pt_category">
                
    	 	</div>
	    </div>
	   
		<div class="swiper-pt-banner swiper-container">
			<ul class="swiper-wrapper" id="pt_banner">
                
			</ul>
			<div class="swiper-pt-pagination"></div>
		</div>
		<ul class="pt_goods_previews" id="pt_goods">

		</ul>
    </div>
    
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/pintuan.js"></script>
    <script type="text/javascript" src="../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../js/jquery.timeCountDown.js" ></script>
</body>
<?php 
include __DIR__.'/../includes/footer.php';
?>
</html>

