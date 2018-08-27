<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>


<div class="goods-category">
	<ol class="step fn-clear add-goods-step clearfix">
		<li class="cur">
			<i class="icon iconfont icon-icoordermsg bbc_seller_color"></i>
			<h6 class="bbc_seller_color"><?=__('STEP 1')?></h6>

			<h2 class="bbc_seller_color"><?=__('选择分类')?></h2>
			<i class="arrow iconfont icon-btnrightarrow"></i>
		</li>
		<li>
			<i class="icon iconfont icon-shangjiaruzhushenqing"></i>
			<h6><?=__('STEP 2')?></h6>

			<h2><?=__('填写信息')?></h2>
			<i class="arrow iconfont icon-btnrightarrow"></i>
		</li>
		<li>
			<i class="icon iconfont icon-zhaoxiangji "></i>
			<h6><?=__('STEP 3')?></h6>

			<h2><?=__('上传图片')?></h2>
			<i class="arrow iconfont icon-btnrightarrow"></i>
		</li>
		<li>
			<i class="icon iconfont icon-icoduigou"></i>
			<h6><?=__('STEP 4')?></h6>

			<h2><?=__('发布成功')?></h2>
			<i class="arrow iconfont icon-btnrightarrow"></i>
		</li>
		<li>
			<i class="icon iconfont icon-pingtaishenhe"></i>
			<h6><?=__('STEP 5')?></h6>

			<h2><?=__('平台审核')?></h2>
		</li>
	</ol>
	<div class="dataLoading" id="dataLoading"><p><?=__('加载中')?>...</p></div>
	<div class="goods-category-list fn-clear clearfix">
		<div class="item_list">
			<ul id="class_div_1">
				<?php
				foreach ($cat_rows as $cat_id=>$cat_row)
				{
				?>
					<li id="<?=$cat_row['cat_id']?>|<?=$cat_row['cat_level']?>" onclick="selClass(this);" class=""><a href="javascript:void(0)"><i class="iconfont icon-angle-right"></i><?=$cat_row['cat_name']?></a></li>
				<?php
				}
				?>
			</ul>
		</div>
		<div class="item_list blank">
			<ul id="class_div_2"></ul>
		</div>
		<div class="item_list blank">
			<ul id="class_div_3"></ul>
		</div>
		<div class="item_list blank">
			<ul id="class_div_4"></ul>
		</div>
	</div>
	<dl class="fn-clear">
		<dt id="span" class="red"><?=__('请选择商品分类')?></dt>
		<dt id="dt" style="display:none;"><?=__('您当前选择的商品类别是')?>：</dt>
		<dd id="dd"></dd>
	</dl>
	<div class="button_next_step">
		<form method="post" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Goods&met=add&typ=e">
			<input type="hidden" name="m" value="goods"/>
			<input type="hidden" id="cat_id" name="cat_id" value="">
			<!--修改商品分类-->
			<?php if ( !empty($common_id) ) { ?>
				<input type="hidden" name="common_id" value="<?= $common_id ?>" />
				<input type="hidden" name="action" value="edit_goods_cat" />
			<?php } ?>
			<input type="submit" class="button  bbc_sellerGray_submit_btns" value="<?=__('下一步，填写商品信息')?>" id="button_next_step" disabled="">
		</form>
	</div>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/goods_add_step.js" charset="utf-8"></script>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>



