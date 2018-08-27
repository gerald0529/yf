<h3>环境检查</h3>
<ol>
	<?php
	foreach ($check_ext_row as $ext_name)
	{
		?>
		<li class="line"><?php echo $ext_name?><span class="<?php echo in_array($ext_name, $loaded_ext_row) ? 'yes' : 'no' ?>"><i class="iconfont"></i><?php echo in_array($ext_name, $loaded_ext_row) ? __('支持') : __('不支持'); ?></span></li>
		<?php
	}
	?>
</ol>
<h3>目录、文件权限检查</h3>
<ol>
	<?php
	foreach ($dir_rows['detail'] as $dir_row)
	{
		?>
		<li class="line"><?php echo ROOT_PATH . $dir_row[0]?><span class="<?php echo $dir_row[1]?>"><i class="iconfont"></i><?php echo $dir_row[2]?></span></li>
		<?php
	}
	?>
</ol>

<script>

	<?php
	if ($check_rs)
	{
	?>
	$('#next_step').removeClass('button-disabled');
	$('#next_step').addClass('button-primary');
	<?php
	}
	?>
</script>