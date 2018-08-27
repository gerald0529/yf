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
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>消费者保障服务</h3>
                <h5>消费者保障服务查看与管理</h5>
            </div>
        </div>
    </div>

    <div class="ncap-form-default">
        <dl class="row">
            <dt class="tit">店铺名称</dt>
            <dd class="opt">
                <ul class="nofloat">
                    <li>
                        <span><?=$data['shop_name']?></span>
                    </li>
                </ul>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">项目名称</dt>
            <dd class="opt">
                <ul class="nofloat">
                    <li>
                        <span><?=$data['contract_type_name']?></span>
                    </li>
                </ul>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">保证金余额</dt>
            <dd class="opt">
                <ul class="nofloat">
                    <li>
                        <span><?=$data['contract_cash']?> 元</span>
                    </li>
                </ul>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">状态</dt>
            <dd class="opt">
                <ul class="nofloat">
                    <li>
                        <span><?=$data['contract_state_text']?></span>
                    </li>
                </ul>
            </dd>
        </dl>
	</div>

        <div class="mod-toolbar-top cf">
            <div class="fr">
                <a class="ui-btn ui-btn-sp" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
            </div>
        </div>
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>

	<?php if($data['cash']==1){ ?>
	<script src="<?=$this->view->js?>/controllers/operation/contract_cash_log_list.js"></script>
	<?php }else{ ?>
    <script src="<?=$this->view->js?>/controllers/operation/contract_log_list.js"></script>
	<?php } ?>
</div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>