
<?php
include $this->view->getTplPath() . '/' . 'supplier_join_header.php';
?>
<script type="text/javascript" src="<?=$this->view->js?>/Touch.js"></script>
<div class="main">
		<div class="modular-select">
			<h2 class="index-title"><?=__('选择个人或者企业供应商入驻')?></h2>
			<ul class="settled-object">
				<li>
					<div>
						<i class="iconfont icon-icon_geren"></i>
						<p><?=__('个人入驻')?></p>
					</div>
					<a href="index.php?ctl=Seller_Supplier_Settled&met=index&op=step1&apply=1" class="btn btn-apply"><?=__('立即申请')?></a>
				</li>
				<li>
					<div>
						<i class="iconfont icon-icon_qiye"></i>
						<p><?=__('企业入驻')?></p>
					</div>
					<a href="index.php?ctl=Seller_Supplier_Settled&met=index&op=step1&apply=2" class="btn btn-apply"><?=__('立即申请')?></a>
				</li>
			</ul>
		</div>
	</div>
<div class="indextip">
	<div class="container"> 
		<span class="title"> <i class="iconfont icon-laba"></i><h3> <?=__('贴心提示')?></h3></span>
		<span class="content"> <?=Web_ConfigModel::value('supplier_slider_tip')?></span> 
    </div>
</div>

<div class="main mt30">
	<h2 class="index-title"> <?=__('入驻流程')?></h2>
	<div class="joinin-index-step"> 
		<span class="step"> <i class="iconfont icon-shangjiaruzhushenqing"></i>  <?=__('签署入驻协议')?> </span> 
		<span class="arrow"></span> 
		<span class="step"> <i class="iconfont icon-xinxitijiao"></i>  <?=__('商家信息提交')?> </span> 
		<span class="arrow"></span> 
		<span class="step"> <i class="iconfont icon-pingtaishenhe"></i>  <?=__('平台审核资质')?> </span> 
		<span class="arrow"></span> 
		<span class="step"> <i class="iconfont icon-jiaonafeiyong"></i>  <?=__('商家缴纳费用')?> </span> 
		<span class="arrow"></span> 
		<span class="step"> <i class="iconfont icon-dianpu2"></i>  <?=__('店铺开通')?> </span> 
	</div>
	<h2 class="index-title"> <?=__('入驻指南')?></h2>
	<div class="joinin-info">
		<ul class="tabs-nav">
			<?php
				foreach ($shop_help as $key => $value) 
				{           
			?>
			<li class="<?php if($key==1){echo "tabs-selected";}?>">
				<h3><?=$value['help_title']?></h3>
			</li>
		   <?php }?>
		</ul>
		<?php 
		foreach ($shop_help as $key => $value) 
		{           
        ?>
			<div class="tabs-panel <?php if($key!=1){?>tabs-hide<?php }?>">
				<?=$value['help_info']?>
			</div>
		<?php }?>
	</div>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>
</body>
</html>