<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
<script type="text/javascript">
//ctrl+F5 增加版本号来清空iframe的缓存的
$(document).keydown(function(event) {
  /* Act on the event */
  if(event.keyCode === 116 && event.ctrlKey){
    var defaultPage = Public.getDefaultPage();
    var href = defaultPage.location.href.split('?')[0] + '?';
    var params = Public.urlParam();
    params['version'] = Date.parse((new Date()));
    for(i in params){
      if(i && typeof i != 'function'){
        href += i + '=' + params[i] + '&';
      }
    }
    defaultPage.location.href = href;
    event.preventDefault();
  }
});
</script>

<style>
.container{position:relative;}
#progress{position: absolute;top:0;left:0;width:100%}
.uploading #progress{z-index:1001;}
.content{padding: 10px;width: 730px;
margin: 0 auto;}
.content li{float: left;width: 20%;}
.content li img{padding:2px;width: 97%;cursor: pointer;}
.content .hover img{border:2px solid rgb(0, 235, 255);padding:0;}
.bar {height:30px;background:#5EC29A;}
.img-warp{
height:444px;
overflow-y:auto;
}
.fileinput-button{
overflow: hidden;
position: fixed;
bottom: 0;
background: #BBB;
color: #ffffff;
display: inline-block;
padding: 4px 0;
margin-bottom: 0;
font-size: 14px;
height: 22px;
line-height: 22px;
text-align: center;
vertical-align: middle;
cursor: pointer;
font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
width:100%;
}
.fileinput-button i{display: inline-block;
vertical-align: middle;
margin: 4px;
height: 16px;
width: 16px;
line-height: 14px;
z-index: 999;
position: relative;
}
.fileinput-button p{
position: absolute;
left: 0;
top: 0;
width:100%;
z-index: 999;
font-size: 16px;
line-height:30px;
}
.fileinput-button.uploading p{z-index: 1002;}
.fileinput-button input{cursor: pointer;
direction: ltr;
font-size: 1000px;
margin: 0;
opacity: 0;
filter: alpha(opacity=0);
position: absolute;
right: 0;
top: 0;
height: 30px;
line-height: 30px;
width: auto;
vertical-align: middle;
z-index: 1000;
}
.imgDiv{
position: relative;
}
.imgDiv .imgControl{
display:none;
width: 100%;
}
.imgDiv.hover .imgControl{
display:block;
position: absolute;
top: 0;
}
.imgDiv.hover .imgControl .del{background: rgb(0, 235, 255);
font-size: 14px;
padding: 0 6px;
position: absolute;
top: 2px;
color: #fff;
right: 2px;
cursor: pointer;
}
.icon-plus{
background: url(/admin/static/default/css/img/ui-icons.png) -210px -17px;
}
</style>
</head>
<body>
<div class="container">

	<div class="img-warp">
		<ul class="content">
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
		</ul>
	</div>
</div>
<!--<script src="http://images.youshang.com/saas/scm/app2_release/js/common/plugins/fileupload/js/vendor/jquery.ui.widget.js?ver=201508241556"></script>
<script src="http://images.youshang.com/saas/scm/app2_release/js/common/plugins/fileupload/js/jquery.iframe-transport.js?ver=201508241556"></script>
<script src="http://images.youshang.com/saas/scm/app2_release/js/common/plugins/fileupload/js/jquery.fileupload.js?ver=201508241556"></script>-->
<script src="./admin/static/default/js/controllers/message/picture.js"></script>
<script src="./admin/static/default/js/controllers/message/picture1.js"></script>
<script src="./admin/static/default/js/controllers/message/picture2.js"></script>
<script src="./admin/static/default/js/controllers/message/picture3.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>