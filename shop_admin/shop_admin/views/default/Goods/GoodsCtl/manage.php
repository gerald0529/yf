<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<style>
body{background: #fff;}
</style>
</head>
<body>

                <form method="post" name="manage-form" id="manage-form" action="<?= Yf_Registry::get('url') ?>?act=goods&amp;op=goods_lockup">
                    <input type="hidden" name="form_submit" value="ok">
                    <input type="hidden" name="common_id_input">

                    <div class="ncap-form-default">
                        <dl class="row">
                            <dt class="tit">违规商品货号</dt>
                            <dd class="opt" id="common_id"></dd>
                        </dl>
                        <dl class="row">
                            <dt class="tit">违规商品名称</dt>
                            <dd class="opt" id="common_name"></dd>
                        </dl>
                        <dl class="row">
                            <dt class="tit">
                                <label for="close_reason">违规下架理由</label>
                            </dt>
                            <dd class="opt">
                                <textarea rows="6" class="ui-input w600"  name="common_state_remark" id="common_state_remark"></textarea>
                            </dd>
                        </dl>
                    </div>
                </form>

<script type="text/javascript">




</script>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>