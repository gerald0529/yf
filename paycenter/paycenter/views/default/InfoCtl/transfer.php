<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<?php if($this->user_info['user_mobile']){?>

	<?php


	?>
<div class="clearfix wrap">
	<form id="form" action="#" method="post" style="width:100%;">
		<div class="transferaccounts-top fl">
			<div class="content-top">
				<span> <?=_('转账到账户')?></span>
			</div>
			<div class="content-bottom ">
				<div class="divautos">
					<div>
						<dl class="clearfix">
							<dt><?=_('收款人：')?></dt>
							<dd>
								<input type="text" class="text text-6" name="user_nickname" id="user_nickname" placeholder="<?=_('收款人账号')?>" onblur="checkUser(this)" onfocus="clear(this)"/>
							</dd>
							<dd><p class="err_msg" id="error_msg_user"></p></dd>
						</dl>
					</div>
					<div>
						<dl class="clearfix">
							<dt><?=_('付款金额 (￥)：')?></dt>
							<dd>
								<input type="text" class="text text-6" name="record_money" id="record_money" onKeyUp="amount(this)" /> </dd>
						</dl>
					</div>
					<div>
						<dl class="clearfix">
							<dt><?=_('付款说明：')?></dt>
							<dd>
								<input type="text" class="text text-6" name="record_desc" id="record_desc"/>
							</dd>
						</dl>
					</div>
					<div>
						<dl class="clearfix ">
							<dt>手机：</dt>
                            <dd><input type="text" class="text text-6" name="mobile" id="mobile" style="border:none;" value="<?=substr($this->user_info['user_mobile'],0,3).'***'.substr($this->user_info['user_mobile'],-3,3)?>" disabled="disabled"/>
                            </dd>
						</dl>
					</div>


					<div>

						<dl class="clearfix ">
							<dt><em class="must" style="color: #f00;">*</em><?=_('图形验证码：')?></dt>
							<dd>
								<input type="text"  name="img_yzm" id="img_yzm" maxlength="6" style="width:86px;vertical-align:top;" placeholder="<?=_('请输入验证码')?>" default="<i class=&quot;i-def&quot;></i><?=_('看不清？点击图片更换验证码')?>"  />
								<img style="vertical-align: middle;" onClick="get_randfunc(this);" title="<?=_('换一换')?>" class="img-code" style="cursor:pointer;" src='./libraries/rand_func.php'/>
							</dd>
						</dl>

               
          </div>


					<div>
						 <dl class="clearfix ">
							<dt><em class="must" style="color: #f00;">*</em><?=_('验证码：')?></dt>
							<dd>
							<input type="text" name="yzm" id="yzm" class="text" value=""  style="width:86px;vertical-align:top;"/>
								<input type="button" class="send" data-type="mobile" value="获取手机验证码" />
							</dd>
						</dl>
					</div>
					<div>
						<dl class="clearfix ">
							<dt><?=_('确认支付密码：')?></dt>
							<dd>
								<input type="password" class="text text-6" name="password" id="password"/>
							</dd>
						</dl>
					</div>
				</div>
			</div>
			<div class="wrap clearfix">
				<div class="pc_trans_btn pc_trans_btn_lf">
					<input class="save btn_active" type="submit" value="<?=_('提交')?>">
				</div>
			</div>
		</div>

	<script>

		//点击验证码
    function get_randfunc(obj)
    {
        var sj = new Date();
        url = obj.src;
        obj.src = url + '?' + sj;
    }

		//验证转账用户是否存在
		function checkUser(e)
		{
			var f = $("#user_nickname").val();

			if(f)
			{
				$.post(SITE_URL + "?ctl=Info&met=getUserBase&typ=json&user_name="+f,
					function(data){
						console.info(data);
						if(data.status == 250)
						{
							$(e).parents().find('#error_msg_user').html('用户不存在');
							 $(".send_user_box").remove();
						}
						else
						{
							$(e).parents().find('#error_msg_user').html('');
							if($(e).parent().find(".send_user_box").size())
							{
								$(e).parent().find(".send_user_box").remove();
							}
							str = "<div class='send_user_box'><div class='send_user_img'><img src='" + data.data.user_avatar + "'></div><div class='send_user_info'><span><?=_('真实姓名：')?>" + data.data.user_realname_mask + "</span><span><?=_('手机号：')?>" + data.data.user_mobile_mask + "</span></div></div>";
							$(e).parent().append(str);
						}
					});
			}
		}
		/**
		 * 实时动态强制更改用户录入
		 * arg1 inputObject
		 **/
		function amount(th){
			var regStrs = [
				['^0(\\d+)$', '$1'], //禁止录入整数部分两位以上，但首位为0
				['[^\\d\\.]+$', ''], //禁止录入任何非数字和点
				['\\.(\\d?)\\.+', '.$1'], //禁止录入两个以上的点
				['^(\\d+\\.\\d{2}).+', '$1'] //禁止录入小数点后两位以上
			];
			for(i=0; i<regStrs.length; i++){
				var reg = new RegExp(regStrs[i][0]);
				th.value = th.value.replace(reg, regStrs[i][1]);
			}
		}
	var icon = '<i class="iconfont icon-exclamation-sign"></i>';
    $(".send").click(function(){
        var type = $(this).attr("data-type");
        //var val = eval(type);

		if(type == 'mobile')
		{
			var val = "<?=$this->user_info['user_mobile']?>";
		}

        msg = "获取手机验证码";
        $(".send").attr("disabled", "disabled");
        $(".send").attr("readonly", "readonly");
        $("#type").attr("disabled", "disabled");
        
        var url = SITE_URL +'?ctl=Info&met=getYzm&typ=json';
        var sj = new Date();
        var pars = 'shuiji=' + sj +'&type='+type +'&val='+val+"&yzm="+$('#img_yzm').val(); 
        
        $.post(url, pars, function (data){
            if(data.status == 200){

                t = setTimeout(countDown,1000);
            }else{
                $('.img-code').click();
                $(".send").attr("disabled", false);
                $(".send").attr("readonly", false);
                $("#type").attr("disabled", false);
                Public.tips.warning(data.msg);
            }
        },'json');


    });
    var delayTime = 60;
    function countDown()
    {
        delayTime--;
        $(".send").val(delayTime + '秒后重新获取');
        if (delayTime == 0) {
            delayTime = 60;
            $(".send").val(msg);
            $(".send").removeAttr("disabled");
            $(".send").removeAttr("readonly");
            clearTimeout(t);
        }
        else
        {
            t=setTimeout(countDown,1000);
        }
    }
	flag = false;
	function checkyzm(){
		$("label.error").remove();
		var yzm = $.trim($("#yzm").val());
		var type = $(".send").attr("data-type");
		var val = "<?=$this->user_info['user_mobile']?>";
		var obj = $(".send");
		if(yzm == ''){
			obj.addClass('red');
			$("<label class='error'>"+icon+"<?=_('请填写验证码')?></label>").insertAfter(obj);
			return false;
		}
		var url = SITE_URL +'?ctl=Info&met=checkYzm&typ=json';
		var pars = 'yzm=' + yzm +'&type='+type +'&val='+val;
		$.post(url, pars, function(a){
				flag = false;
				if (a.status == 200)
				{
					flag = true;
				}
				else
				{
					obj.addClass('red');
					$("<label class='error msg-box'>"+icon+"<?=_('验证码错误')?></label>").insertAfter(obj);
					return flag;
				}
		});
		return flag;
	}	
		
		//表单提交
		$('#form').validator({
			ignore: ':hidden',
			theme: 'yellow_right',
			timely: 1,
			stopOnError: false,
			rules:{
				checkUserNickname:function()
				{
					if($("#user_nickname").val() == "<?=$this->user_info['user_nickname']?>") {
						return '不能转账给自己';
					}

				}
			},
			fields: {
				'user_nickname': 'required;checkUserNickname',
				'record_money': 'required;phone',
				'record_desc': 'required;',
				'password':'required',
			},
			valid:function(form){
				var me = this;
				// 提交表单之前，hold住表单，防止重复提交
				me.holdSubmit();
				var user_nickname = $("#user_nickname").val();
				var record_money = $("#record_money").val();
				var record_desc    = $("#record_desc").val();
				var mobile      =  "<?=$this->user_info['user_mobile']?>";
				var yzm      = $("#yzm").val();
				var password      = $("#password").val();
				var ajax_url = '<?= Yf_Registry::get('url');?>?ctl=Info&met=addTransfer&typ=json';
				//表单验证通过，提交表单
				$.ajax({
					url: ajax_url,
					data:{user_nickname:user_nickname,record_money:record_money,record_desc:record_desc,mobile:mobile,yzm:yzm,password:password},
					success:function(a){
                        if(a.status == 200)
                        {
                            Public.tips.success("<?=_('操作成功')?>");
                            location.href= "<?= Yf_Registry::get('url');?>?ctl=Info&met=recordlist";
                        }
                        else
                        {
                            if(typeof(a.msg) == 'undefined' || !a.msg){
                                Public.tips.error("<?=__('操作失败')?>"); 
                            }else{
                                Public.tips.error(a.msg); 
                            }

                        }

                        // 提交表单成功后，释放hold，如果不释放hold，就变成了只能提交一次的表单
                        me.holdSubmit(false);       
					},
					error:function ()
					{
						me.holdSubmit(false);
					}

			});
		}

		});
	</script>
	
	<div class="transferaccounts-bottom fr">
		<div class="content-top ">
			<span><?=_('使用中遇到的问题?')?></span>
		</div>
		<div class="content-center ">
			<div class="pulibc-divcenter ">
				<h4><?=_('1.我没有网付宝账户，可以付款吗？')?></h4>
				<p><?=_('答：可以，只要您有银行卡就可以开通快捷进行付款，或者您也可以让他人帮您付款。')?></p>
			</div>
			<div class="pulibc-divcenter ">
				<h4><?=_('2.（提醒卖家知识）网付宝客服会用QQ来要求卖家交消保金吗？')?></h4>
				<p><?=_('答：不会，远丰卖家请注意店铺交易安全，谨防假冒客服，以审核消保、二次审核为由，诱导转账。')?></p>
			</div>
			<div class="pulibc-divcenter ">
				<h4><?=_('3.（提醒卖家知识）网购时，如何谨防卖家诱导加QQ要求转账付款？')?></h4>
				<p><?=_('答：网购时，请使用网付宝担保交易付款，谨防卖家诱导加QQ、使用转账付款；卖家请注意店铺交易安全，谨防假冒客服，以审核消保、二次审核为由诱导转账。')?></p>
			</div>
			<div class="pulibc-divcenter ">
				<h4><?=_('4.（提醒买家知识）淘宝购物，卖家加QQ要求转账付款，安全吗？')?></h4>
				<p><?=_('答：远丰商城购物时，请使用支付宝担保交易付款，谨防卖家诱导加QQ、使用转账付款。')?></p>
			</div>
		</div>
	</div>
</div>
	<!-- <div class="wrap clearfix">
		<div class="pc_trans_btn pc_trans_btn_lf">
			<input class="save" type="submit" value="<?=_('提交')?>">
		</div>
	</div> -->
</form>
<?php }else{?>
    <div class="security-tips">转账前必须先进行手机绑定，点击这里进行<a href="<?=Yf_Registry::get('ucenter_api_url')?>?ctl=User&met=security&op=mobile">手机绑定</a></div>
	<?php }?>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>