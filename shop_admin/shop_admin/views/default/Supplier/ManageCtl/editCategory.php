<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<style>

.ui-jqgrid tr.jqgrow .img_flied{padding: 1px; line-height: 0px;}
.img_flied img{width: 100px; height: 30px;}

</style>
  <div class="wrapper page" >

  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>店铺设置</h3>
          <h5>店铺设置-单个店铺的经营类目</h5>
      </div>
      </div>
  </div>

               <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em>
        </div>
        <ul>
            <li>删除店铺的经营类目会造成相应商品下架，请谨慎操作。</li>
        </ul>
    </div>



    <div class="mod-toolbar-top cf">
		<div class="left" style="float: left;">
		</div>
                <div class="fr">
                    <a href="#" class="ui-btn ui-btn-sp mrb" id="btn-add">新增<i class="iconfont icon-btn03"></i></a><a class="ui-btn" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
                </div>
	</div>
   
    <div class="grid-wrap">
		<table id="grid">
		</table>
		<div id="page"></div>
    </div>
    
</div>




<script type="text/javascript" src="<?=$this->view->js?>/controllers/supplier/index/shop_editCategory_list.js" charset="utf-8"></script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>