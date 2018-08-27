<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
</head>

</head>
<body>

<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>云版商城</h3>
                <h5>商城云版用户的开通和管理</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a <?=request_string('cloud_type') ? '' : 'class="current"'?> href="<?= Yf_Registry::get('url') ?>?ctl=User_Base&met=shopAccount&typ=e&cloud_type=0"><span>云版商城</span></a></li>
                <li><a <?=request_string('cloud_type') ? 'class="current"' : ''?> href="<?= Yf_Registry::get('url') ?>?ctl=User_Base&met=shopAccount&typ=e&cloud_type=1"><span>云版商城(独立用户中心 支付中心)</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <li>对云版用户信息进行操作</li>
        </ul>
    </div>
    <div class="mod-search cf">
        <div class="fr">
            <a href="#" class="ui-btn ui-btn-sp mrb" id="btn-add">新增<i class="iconfont icon-btn03"></i></a>
            <a class="ui-btn" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
        </div>
    </div>
    <div class="cf">
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>
    </div>
</div>
<script>
var cloud_type = <?=request_int('cloud_type')?>;
</script>
<script src="./ucenter_admin/static/default/js/controllers/user/base/shop_account.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>