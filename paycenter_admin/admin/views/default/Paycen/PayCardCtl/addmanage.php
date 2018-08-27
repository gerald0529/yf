<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>

    <link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?=$this->view->css?>/jquery/plugins/validator/jquery.validator.css">
    <script type="text/javascript" src="<?=$this->view->js?>/libs/jquery/plugins/validator/jquery.validator.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js?>/libs/jquery/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

    <!--<script src="./admin/static/default/js/controllers/my_lightbox.js" language="javascript"></script>-->
    <style>
        body{background:#fff;}
        .mod-form-rows .label-wrap{font-size:12px;width:60px;}
        .mod-form-rows .row-item{padding-bottom:15px;margin-bottom:0;}/*兼容IE7 ，重写common的演示*/
        .manage-wrapper{margin:20px auto 10px;width:450px;}
        .manage-wrap .ui-input{width:170px;}
        .base-form{*zoom:1;}
        .base-form:after{content:'.';display:block;clear:both;height:0;overflow:hidden;}
        .base-form li{float:left;width:290px;}
        .base-form li.odd{padding-right:20px;}
        .base-form li.last{width:600px}
        .base-form li.last .ui-input{width:500px;}
        .manage-wrap textarea.ui-input{width:500px;height:48px;line-height:16px;overflow:hidden;}
        .contacters{margin-bottom:15px;}
        .contacters h3{margin-bottom:10px;font-weight:normal;}
        .remark .row-item{padding-bottom:0;}
        .mod-form-rows .ctn-wrap{overflow:visible;}
        .grid-wrap .ui-jqgrid{border-width:1px 0 0 1px;}
        .ui-combo-wrap .input-txt{width: 160px;}
        .cardimg{width: 400px;height: 168px;margin: 0 auto;border: 1px solid #E2E2E2;text-align: center;}

    </style>
    </head>
    <body>
    <div class="manage-wrapper">
        <div id="manage-wrap" class="manage-wrap">
            <form id="manage-form" action="">
                <ul class="mod-form-rows base-form cf" id="base-form">
                    <li class="row-item ">
                        <div class="label-wrap"><label for="card_id">请选择卡号</label></div>
                        <div class="ctn-wrap">
                            <span id="card_id" value="请选择卡号" name="card_id"></span>
                        </div>
                    </li>
                    <li class="row-item ">
                        <div class="label-wrap"><label for="card_sum">生成卡片数</label></div>
                        <div class="ctn-wrap"><input type="text" class="ui-input" name="card_sum" id="card_sum"></div>
                    </li>

                </ul>
                <div class="contacters">
                    <div class="grid-wrap">
                        <table id="grid"></table>
                    </div>
                </div>
            </form>
        </div>
        <div class="hideFile dn">
            <input type="text" class="textbox address" name="address" id="address" autocomplete="off" readonly>
        </div>
    </div>
    <script src="./admin/static/default/js/controllers/paycard/card_addmanage.js"></script>

<?php
include TPL_PATH . '/'  . 'footer.php';
?>