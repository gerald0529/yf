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
                            <dt class="tit">审核商品货号</dt>
                            <dd class="opt" id="common_id"></dd>
                        </dl>
                        <dl class="row">
                            <dt class="tit">审核商品名称</dt>
                            <dd class="opt" id="common_name"></dd>
                        </dl>
						<dl class="row">
							  <dt class="tit">
								<label>审核通过</label>
							  </dt>
							  <dd class="opt">
								<div class="onoff">
								  <label for="verify_enabled" class="cb-enable selected" title="是">是</label>
								  <label for="verify_disabled" class="cb-disable" title="否">否</label>
								  <input id="verify_enabled" name="common_verify" checked="checked" value="1" type="radio">
								  <input id="verify_disabled" name="common_verify" value="0" type="radio">
								</div>
								<p class="notic"></p>
							  </dd>
						</dl>
                        <dl class="row">
                            <dt class="tit">
                                <label for="common_verify_remark">审核备注</label>
                            </dt>
                            <dd class="opt">
                                <textarea rows="2" class="ui-input w600"  name="common_verify_remark" id="common_verify_remark"></textarea>
                            </dd>
                        </dl>
                    </div>
                </form>

<script type="text/javascript">




</script>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/verify_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>