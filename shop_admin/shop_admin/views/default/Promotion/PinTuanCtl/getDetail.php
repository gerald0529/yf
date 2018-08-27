<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
    <link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
    </head>
    <style>
        .manage-wrap .ncap-form-default,
        .manage-wrap .ncap-form-all { width: 96%; margin: 0 auto; padding: 0; }
        .manage-wrap .ncap-form-default dt.tit { text-align: right; width: 20%; padding-right: 2%; }
        .manage-wrap .ncap-form-default dd.opt { text-align: left; width: 77%; }
        .manage-wrap .ncap-form-all dl.row { padding: 8px 0; }
        .manage-wrap .ncap-form-all dt.tit { font-size: 12px; font-weight: 600; line-height: 24px; background-color: transparent; height: 24px; padding: 4px; }
        .manage-wrap .ncap-form-all dd.opt { font-size: 12px; padding: 0; border: none; }
        .manage-wrap .ncap-form-all .search-bar { padding: 4px; }
        .manage-wrap .bot { text-align: center; padding: 12px 0 10px 0 !important; }
        .manage-wrap .rule-goods-list { position: relative; z-index: 1; overflow: hidden; max-height: 200px; }
        .manage-wrap .rule-goods-list ul { font-size: 0; }
        .manage-wrap .rule-goods-list ul li { font-size: 12px; vertical-align: top; display: inline-block; width: 48%; padding: 1%; }
        .manage-wrap .rule-goods-list ul li img { float: left; width: 32px; height: 32px; margin-right: 5px; }
        .manage-wrap .rule-goods-list ul li a,
        .manage-wrap .rule-goods-list ul li span { color: #555; line-height: 16px; white-space: nowrap; text-overflow: ellipsis; display: block; float: left; width: 180px; height: 16px; overflow: hidden; }
        .manage-wrap .rule-goods-list ul li span { color: #AAA; }
        .manage-wrap .rule-goods-list ul li img {
            float: left;
            width: 32px;
            height: 32px;
            margin-right: 5px;
        }
        .cou-rule { padding: 5px; border: dotted 1px #E7E7E7; margin-bottom: 10px; overflow: hidden; }
        .cou-rule span { display: block }
    </style>
    <body>
    <div id="manage-wrap" class="manage-wrap">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">拼团名称</dt>
                <dd class="opt" id="pintuan_name"></dd>
            </dl>
            <dl class="row">
                <dt class="tit">店铺名称</dt>
                <dd class="opt" id="shop_name"></dd>
            </dl>
            <dl class="row">
                <dt class="tit">拼团时间段</dt>
                <dd class="opt"><span id="active_time"></dd>
            </dl>
            <dl class="row">
                <dt class="tit">成团人数</dt>
                <dd class="opt"><span id="person_num"></dd>
            </dl>
            <dl class="row">
                <dt class="tit">拼团规则</dt>
                <dd class="opt">
                    <div class="rule-goods-list">
                        <?php if (! empty($data)) { ?>
                            <?php foreach ($data as $item) { ?>
                                <div class="cou-rule">
                                    <span>商品名称:<strong><?= $item['goods_name']; ?></strong></span>
                                    <span>拼团库存:<strong><?= $item['goods_stock']; ?></strong></span>
                                    <span>商品原价:<strong><?= $item['price_ori']; ?></strong></span>
                                    <span>单人买价格:<strong><?= $item['price_one']; ?></strong></span>
                                    <span>拼团价格:<strong><?= $item['price']; ?></strong></span>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </dd>
            </dl>
        </div>
    </div>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

<script>
    $(function () {
        var data = frameElement.api.data.rowData;

        $('#pintuan_name').text(data.name);
        $('#shop_name').text($(data.shop_name).text());
        $('#active_time').text(data.start_time + '~' + data.end_time);
        $('#person_num').text(data.person_num);
    })
</script>
