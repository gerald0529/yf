<span><?=__('欢迎来')?><?=Web_ConfigModel::value("site_name") ?></span>
<?php echo empty($this->userInfo) ? '<a href="' . Yf_Registry::get('url') . '?ctl=Login&met=login"> '.__('请登录').' </a> <a href="' . Yf_Registry::get('url') . '?ctl=Login&met=reg">'.__('免费注册').' </a> ' : ' <a href="./index.php?ctl=Buyer_Index&met=index" class="user-name"> ' . $this->userInfo['user_name'] . ' </a> <a href="' . Yf_Registry::get('url') . '?ctl=Login&met=loginout"> ['.__('退出').' ]</a>' ?>
<?php
$d = ob_get_contents();
ob_end_clean();
ob_start();

$data[] = $d;
?>

<div class="tright_content">
    <p class="user_head">
		<a href="./index.php?ctl=Buyer_Index&met=index">
			<img src="<?= Yf_Registry::get('ucenter_api_url') ?>?ctl=Index&met=img&user_id=<?= @Perm::$userId ?>"/>
		</a>
	</p>
	<p class="hi"><span><?=__('Hi~你好！')?></span></p>
	<?php echo empty($this->userInfo) ? '<p><a href="' . Yf_Registry::get('url') . '?ctl=Login&met=login" class="login">
	<span class="iconfont icon-icondenglu"></span>'.__('请登录').'</a></p><p><a class="register" href="' . Yf_Registry::get('url') . '?ctl=Login&met=reg"><i class="iconfont icon-icoedit"></i>'.__('免费注册').'</a></p>' : '<p><a class="ellipsis" href="./index.php?ctl=Buyer_Index&met=index">' . $this->userInfo['user_name'] . '</a></p>' ?>

	<div class="prom">
		<p><span class="iconfont icon-tuihuobaozhang"></span><?=__('退货保障')?></p>
		<p><span class="iconfont icon-shandiantuikuan"></span><?=__('极速退款')?></p>
	</div>
	<div class="cooperation">
		<h3><a href="index.php?ctl=Seller_Shop_Settled&met=index" class="apply"><?=__('招商入驻')?></a></h3>
		<p><a href="index.php?ctl=Seller_Shop_Settled&met=index" class="apply"><img src="<?= $this->view->img ?>/icon_ruzhu.png"/></a></p>
		<?php if(@Perm::$shopId){ ?>
		<p><a href="index.php?ctl=Seller_Index&met=index" class="apply"><?=__('进入商家中心')?></a></p>
		<?php }else{ ?>
		<p><a href="index.php?ctl=Seller_Shop_Settled&met=index" class="apply"><?=__('申请商家入驻')?></a></p>
		<?php } ?>
	</div>
</div>
<?php
$d = ob_get_contents();
ob_end_clean();
ob_start();

$data[] = $d;
?>