<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<div class="memberguarantee-top content-public">
	<img src="default/images/huiyuanbanner.png" />
</div>
<div class="memberguarantee-bottom content-public">
	<div class="content-top">
		<div class="title">保障流程</div>
		<div class="content clearfix">
			<div class="clearfix" style="margin:0 20px;">
				<div class=" memberguarantee-public ">
					<dl>
						<dt>
							<span class="disspan">1、</span>
							<img src="default/images/memberguarantee1.png" /></dt>
						<dd>致电021-61848656提交理赔申请</dd>
					</dl>
				</div>
				<div class="memberguarantee-public ">
					<dl>
						<dt>
							<span class="disspan">2、</span>
							<img src="default/images/memberguarantee2.png" /></dt>
						<dd>我们将在3-5个工作日内完成审核</dd>
					</dl>
				</div>
				<div class="memberguarantee-public mar600">
					<dl>
						<dt>
							<span class="disspan">3、</span>
							<img src="default/images/memberguarantee3.png" /></dt>
						<dd>赔付完成，款项最快24小时自动到账</dd>
					</dl>
				</div>
				<div class="cline">
					提交审核
					<span class="triangle"></span>
				</div>
				<div class="cline-2">
					审核通过
					<span class="triangle triangled"></span>
				</div>
			</div>
		</div>

	</div>
	<div class="content-center">
		<div class="title">交易安全与账户安全</div>
		<div class="content clearfix">
			<div class="boxs-public">
				<div class="left"><img src="default/images/memberguarantee4.png"></div>
				<div class="right">
					<h3>强力打击钓鱼网站</h3>
					<p>依托远丰公司云引擎URL恶意属性判别 方法等专利技术，强力打击钓鱼网站， 大力提升网络环境的安全级别。
					</p>
				</div>
			</div>

			<div class="boxs-public">
				<div class="left"><img src="default/images/memberguarantee5.png"></div>
				<div class="right">
					<h3>全方位交易风险监控</h3>
					<p>24小时对账户及资金情况进行实时监 控，严厉打击异常交易、虚假交易、 盗卡等行为。
					</p>
				</div>


			</div>
			<div class="boxs-public">
				<div class="left">
					<img src="default/images/memberguarantee6.png"></div>
				<div class="right">
					<h3>账户风险实时监控</h3>
					<p>财付通风险控制系统7×24小时实时监 控，保障账户资金安全。
					</p>
				</div>
			</div>
			<div class="boxs-public">
				<div class="left">
					<img src="default/images/memberguarantee7.png"></div>
				<div class="right">
					<h3>资金异常及时通知</h3>
					<p>当账户资金发生变动，将通过手机、邮 件等方式实时通知您，接收账户变动通 知。
					</p>
				</div>
			</div>
			<div class="boxs-public">
				<div class="left">
					<img src="default/images/memberguarantee8.png"></div>
				<div class="right">
					<h3>安全产品加固安全</h3>
					<p>Pay Center提供多种安全产品，提升 账户安全性，加强对资金的保护。</p>
				</div>
			</div>
			<div class="boxs-public">
				<div class="left">
					<img src="default/images/memberguarantee9.png"></div>
				<div class="right">
					<h3>隐私保护</h3>
					<p>严格执行国家相关法律法规，自动隐 藏支付密码、身份证号码等敏感信息， 为您提供完善的隐私保护策略。
					</p>
				</div>
			</div>
		</div>
	</div>
	<div class="content-bottom ">
		<!--轮播图-->
		<div class="title">知识安全</div>
		<ul class="rslides clearfix">
			<li>
				<img src="default/images/safety knowledge-1.png">
			</li>
			<li>
				<img src="default/images/safety knowledge-2.png">
			</li>
		</ul>




	</div>
</div>

<!--轮播图-->
<script src="<?=$this->view->js?>/responsiveslides.js">
</script>
<script>
	$(function () {
		$(".rslides").responsiveSlides();
	});
</script>


<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>