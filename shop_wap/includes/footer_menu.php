 <?php 

$footer_menu = [
	'首页'=>'/index.html',
	'分类'=>'/tmpl/product_first_categroy.html',
	'购物车'=>'/tmpl/cart_list.html',
	'我的'=>'/tmpl/member/member.html',
];

?> 

 <div class="footer bort1">
	<ul class="clearfix">
		<?php foreach($footer_menu as $k=>$v){?>
			<li <?php if(menu_active($v)){?> class="active"<?php }?> >
				<a href="<?php echo $v;?>"> 
						<i class="icon"></i>
						<h3><?php echo $k; ?></h3>
				</a> 
			</li>
		 <?php } ?>
	</ul>
</div>  