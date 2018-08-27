<?php 
if (!defined('ROOT_PATH')) {exit('No Permission');}
include $this->view->getTplPath() . '/'  . 'header.php';

?>
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>消息管理&nbsp;</h3>
                <h5>消息管理</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current" ><span>消息模板</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em>
        </div>
    </div>

    <div class="wrapper">
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>
    </div>

</div>
<script type="text/javascript">
    $('#tab-base li:first').addClass('current');

</script>

<script src="<?= Yf_Registry::get('base_url') ?>/ucenter_admin/static/default/js/controllers/message.js"></script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>