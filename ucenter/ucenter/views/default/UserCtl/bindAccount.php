<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'header.php';
?>
<link rel="stylesheet" href="<?= $this->view->css ?>/security.css">
<div>

<div class="bind-account">
	<ul>
		<li>
			<div class="clearfix">
				<div class="fl"><i class="icon qq"></i><span>QQ</span></div>

				<?php if($qq_bind){?>
					<div class="fr right">
						<div class="fl status active">已绑定</div>
						<div class="fr"><a href="javascript:;" class="btn-bind1 active">立即解绑</a></div>
					</div>
				</div>
				<div class="dropdown-details">
					<p>您正在使用商城账号关联1个QQ账号</p>
					<p class="prominent">您的商城账号为：<?=$user_account?></p>
					<p class="prominent">您的QQ账号为：<?=$qq_bind['bind_nickname']?></p>
					<p>解除绑定后使用此账号登录，解除绑定后的账号正常使用，订单等信息不会丢失，忘记密码请在登录页面找回！</p>
					<p>该账号下的QQ号码全部解绑</p>
					<a href="javascript:;" class="btn-bind2 active btn-unbind" type="qq">立即解绑</a>
					<i class="btn-pack-up"></i>
				</div>
				<?php }else{?>
					<div class="fr right">
						<div class="fl status">未绑定</div>
						<div class="fr"><a href="javascript:;" class="btn-bind1">立即绑定</a></div>
					</div>
				</div>
				<div class="dropdown-details">
					<p>您正在使用商城账号关联1个QQ账号</p>
					<p class="prominent">绑定后，可以使用QQ账号登录购物</p>
					<a href="javascript:;" class="btn-bind2 btn-bind" type="qq">立即绑定</a>
					<i class="btn-pack-up"></i>
				</div>
				<?php }?>

		</li>
		<li>
			<div class="clearfix">
				<div class="fl"><i class="icon wechat"></i><span>微信</span></div>
				<?php if($wx_bind){?>
					<div class="fr right">
						<div class="fl status active">已绑定</div>
						<div class="fr"><a href="javascript:;" class="btn-bind1 active">立即解绑</a></div>
					</div>
				</div>
				<div class="dropdown-details">
					<p>您正在使用商城账号关联1个微信账号</p>
					<p class="prominent">您的商城账号为：<?=$user_account?></p>
					<p class="prominent">您的微信账号为：<?=$wx_bind['bind_nickname']?></p>
					<p>解除绑定后使用此账号登录，解除绑定后的账号正常使用，订单等信息不会丢失，忘记密码请在登录页面找回！</p>
					<p>该账号下的微信账号全部解绑</p>
					<a href="javascript:;" class="btn-bind2 active btn-unbind" type="wx">立即解绑</a>
					<i class="btn-pack-up"></i>
				</div>
				<?php }else{?>
					<div class="fr right">
						<div class="fl status">未绑定</div>
						<div class="fr"><a href="javascript:;" class="btn-bind1">立即绑定</a></div>
					</div>
				</div>
				<div class="dropdown-details">
					<p>您正在使用商城账号关联1个微信账号</p>
					<p class="prominent">绑定后，可以使用微信账号登录购物</p>
					<a href="javascript:;" class="btn-bind2 btn-bind" type="wx">立即绑定</a>
					<i class="btn-pack-up"></i>
				</div>
				<?php }?>

		</li>
		<li>
			<div class="clearfix">
				<div class="fl"><i class="icon wb"></i><span>微博</span></div>
				<?php if($wb_bind){?>
					<div class="fr right">
						<div class="fl status active">已绑定</div>
						<div class="fr"><a href="javascript:;" class="btn-bind1 active">立即解绑</a></div>
					</div>
				</div>
				<div class="dropdown-details">
					<p>您正在使用商城账号关联1个微博账号</p>
					<p class="prominent">您的商城账号为：<?=$user_account?></p>
					<p class="prominent">您的微博账号为：<?=$wb_bind['bind_nickname']?></p>
					<p>解除绑定后使用此账号登录，解除绑定后的账号正常使用，订单等信息不会丢失，忘记密码请在登录页面找回！</p>
					<p>该账号下的微博账号全部解绑</p>
					<a href="javascript:;" class="btn-bind2 active btn-unbind" type="wb">立即解绑</a>
					<i class="btn-pack-up"></i>
				</div>
				<?php }else{?>
					<div class="fr right">
						<div class="fl status">未绑定</div>
						<div class="fr"><a href="javascript:;" class="btn-bind1">立即绑定</a></div>
					</div>
				</div>
				<div class="dropdown-details">
					<p>您正在使用商城账号关联1个微博账号</p>
					<p class="prominent">绑定后，可以使用微博账号登录购物</p>
					<a href="javascript:;" class="btn-bind2 btn-bind" type="wb">立即绑定</a>
					<i class="btn-pack-up"></i>
				</div>
				<?php }?>
		</li>
	</ul>
	<script>
		$(function(){
			$(".bind-account li .btn-bind1").click(function(){
				$(this).parent().parent().parent().next(".dropdown-details").slideDown();
			})
			$(".bind-account li .btn-pack-up").click(function(){
				$(this).parent().slideUp();
			})

			//解除绑定 - 弹框显示
			$(".btn-unbind").click(function(){
			var t = $(this).attr('type');

			if(t == 'qq')
			{
				$(".type").html('QQ');
				$(".type").attr('type','2');
			}

			if(t == 'wx')
			{
				$(".type").html('微信');
				$(".type").attr('type','3');
			}

			if(t == 'wb')
			{
				$(".type").html('微博');
				$(".type").attr('type','1');
			}

			$(".dialog-alert").show();
			})

			//立即绑定
			$('.btn-bind').click(function(){
				//判断用户有没有绑定过手机，如果没有绑定手机则跳转到绑定手机页面
				if('<?=$mobile_bind?>')
				{
					var t = $(this).attr('type');

					//获取当前页面的地址
					var url = encodeURIComponent(window.location.href);

					if(t == 'qq')
					{
						location.href = '<?=$qq_url?>'+'&callback='+url;
					}

					if(t == 'wx')
					{
						location.href = '<?=$wx_url?>'+'&callback='+url;
					}

					if(t == 'wb')
					{
						location.href = '<?=$wb_url?>'+'&callback='+url;
					}

				}
				else
				{
					location.href = './index.php?ctl=User&met=security&op=mobile';
				}



			})
		})
	</script>
</div>


	<!-- 弹框 -->
	<div class="dialog-alert">
		<div class="dis-table">
			<div class="table-cell">
				<div class="bind-tips">
					<h3 class="Js_title">您确定要解除绑定？<i class="iconfont icon-cuowu fr btn-cancel"></i></h3>
					<div class="tips-text">
						<div class="dis-table">
							<div class="table-cell">
								<p><i class="icon-unbind Js-icon"></i><!-- 绑定成功的话，i标签的class改为icon-success-->
								<span class="text Js_body">您确定要解除绑定<em class="type" ></em>账号？</span></p>
								<div class="Js_operation"><a href="javascript:;" class="btn btn-sure">确定</a><a href="javascript:;" class="btn btn-cancel">取消</a></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(function(){
			//取消解除绑定，关闭解除绑定页面
			$(".dialog-alert").on("click", ".btn-cancel", function() {
			  $(".dialog-alert").hide();
			});

			//确认解除绑定
			$(".btn-sure").click(function(){
				var type = $('.type').attr('type');

				//解除用户绑定
				var ajaxurl = './index.php?ctl=User&met=unbind&typ=json&type='+type;
				$.ajax({
					type: "POST",
					url: ajaxurl,
					dataType: "json",
					async: false,
					success: function (respone)
					{
						if(respone.status == 250)
						{
							unbindSuccess(type);
					 		Public.tips.success("<?=_('解除绑定成功')?>");
						}
						else
						{
							Public.tips.error("<?=_('解除绑定失败')?>");
						}

						location.reload();
					}
				});
			})
		});

		//成功解除绑定
		function unbindSuccess(type) {
			var name;
			switch (type) {
				case "2":
					name = "QQ";
					break;
				case "3":
					name = "微信";
					break;
				case "1":
					name = "微博";
					break;
			}

			$(".dialog-alert").find(".Js_title").html("解绑成功");
			$(".Js_body").html("您已成功解绑"+ name +"账号");
			$("div.Js_operation").remove();
			$(".Js-icon").removeClass("icon-unbind").addClass("icon-success");
		}
	</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>