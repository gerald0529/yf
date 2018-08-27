<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<link href="<?= $this->view->css ?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<style>

    .dx-warning {
        background: #FFF;
        border: 5px solid #ffba00;
        padding: 20px;
        margin-bottom: 30px
    }

    .dx-warning h2 {
        margin: 0;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0
    }

    .dx-warning ol {
        margin-top: 20px
    }

    .dx-warning li {
        margin: 5px 0
    }

</style>
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>版本管理&nbsp;</h3>
                <h5>更新</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current" ><span>更新管理中心</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=updatePay"><span>更新PayCenter</span></a></li>
            </ul>
        </div>
    </div>

    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p>
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <li>重要：在升级前，请备份您的数据库和文件。</li>
            <li>&nbsp;</li>
        </ul>
    </div>

    
    <div class="mod-toolbar-top cf">
          
           
           <?php  
            //升级按钮
            $url = $_SERVER['SCRIPT_NAME'];
            $url = str_replace('/index.php','',$url);
            Yf_Hash::setKey("token_".Yf_Registry::get('shop_api_key'));

            $hash = Yf_Hash::encrypt(http_build_query(array('id'=>$_COOKIE['id'],'key'=>$_COOKIE['key'])));
            $hash = urlencode($hash);
            ?>
           <iframe src="<?php  echo $url.'/upgrade/index.php?id='.Yf_Registry::get('shop_api_key').'&key='.$hash;?>" 
                style=" border:0; width:100%; min-height:700px;">
               

    </div>

</div>

 
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

