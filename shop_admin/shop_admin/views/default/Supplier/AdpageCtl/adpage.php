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
    
<div class="wrapper page" >
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>供应商模板风格</h3>
                <h5>商城供应商模板及广告设计</h5>
            </div>
            <ul class="tab-base nc-row">
                <?php
                $data_theme = $this->getUrl('Config', 'siteTheme', 'json', null, array('config_type'=>array('site')));
    
                $theme_id = $data_theme['theme_id']['config_value'];
    
                foreach ($data_theme['theme_row'] as $k => $theme_row)
                {
                    if ($theme_id == $theme_row['name'])
                    {
                        $config = $theme_row['config'];
                        break;
                    }
                }
                ?>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=supplier_index&config_type%5B%5D=supplier_index"><span>供应商首页幻灯片</span></a></li>
                <li><a class="current"  href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Adpage&met=adpage"><span>供应商首页模板</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=supplier_index_img&config_type%5B%5D=supplier_index_img"><span>供应商首页小图</span></a></li>
            </ul>
        </div>
    </div>



         <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <li>排序越小越靠前，可以控制板块显示先后。</li>
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
       <script type="text/javascript" src="<?=$this->view->js?>/controllers/supplier/adv_adpage_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>