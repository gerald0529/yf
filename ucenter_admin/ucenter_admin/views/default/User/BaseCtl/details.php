<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<div class="ncap-form-default">
	<dl class="row">
		<dt class="tit">
			<label class="licence_domain" for="licence_domain"><em>*</em>用户名称 :</label>
		</dt>
		<dd class="opt"><?=$data['user_name']?></dd>

	</dl>
	<dl class="row">
		<dt class="tit">
			<label class="licence_domain" for="licence_domain"><em>*</em>真实姓名 :</label>
		</dt>
		<dd class="opt"><?=$data['user_truename']?></dd>

	</dl>
	<dl class="row">
		<dt class="tit">
			<label class="licence_domain" for="licence_domain"><em>*</em>性别 :</label>
		</dt>
		<dd class="opt"><?php if($data['user_gender'] == 0){echo "女";}elseif($data['user_gender'] == 1){echo "男";}else{echo "保密";}?></dd>

	</dl>
	<dl class="row">
		<dt class="tit">
			<label class="licence_domain" for="licence_domain"><em>*</em>生日 :</label>
		</dt>
		<dd class="opt"><?=$data['user_birth']?></dd>

	</dl>
	<dl class="row">
		<dt class="tit">
			<label class="licence_domain" for="licence_domain"><em>*</em>用户手机 :</label>
		</dt>
		<dd class="opt"><?=$data['user_mobile']?></dd>

	</dl>
	<dl class="row">
		<dt class="tit">
			<label class="licence_domain" for="licence_domain"><em>*</em>用户邮箱 :</label>
		</dt>
		<dd class="opt"><?=$data['user_email']?></dd>

	</dl>
	<dl class="row">
		<dt class="tit">
			<label class="licence_domain" for="licence_domain"><em>*</em>用户地址 :</label>
		</dt>
		<dd class="opt"><?=$data['user_area']?></dd>

	</dl>
	<?php foreach ($data['user_option_rows'] as $user_option_row):?>
		<?php if($user_option_row['reg_option_name']):?>
			<dl class="row">
				<dt class="tit">
					<label class="licence_domain" for="licence_domain"><em>*</em><?=$user_option_row['reg_option_name']?> :</label>
				</dt>
				<dd class="opt"><?=$user_option_row['user_option_value']?></dd>
			</dl>
		<?php endif;?>
	<?php endforeach; ?>
</div>