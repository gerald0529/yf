
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/buyer.js"></script>

<script type="text/javascript" src="<?=$this->view->js?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>


</div>
</div>
</div>
<div class="bort1 mrt60">
	<div class="wrapper">
		<br />
		<p class="copyright"><?=Web_ConfigModel::value('copyright')?></p>
	</div>
</div>
</div>
<link href="<?= $this->view->css ?>/jquery/plugins/validator/jquery.validator.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?= $this->view->js ?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

<link href="<?= $this->view->css ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this->view->js ?>/plugins/jquery.dialog.js"></script>
<p class="statistics_code"><?php echo Web_ConfigModel::value('icp_number') ?></p>
<p class="statistics_code"><?php echo Web_ConfigModel::value('statistics_code') ?></p>
</body>
</html>