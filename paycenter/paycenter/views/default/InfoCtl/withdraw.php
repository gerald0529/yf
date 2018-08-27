<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

<?php if($this->user_info['user_mobile']){?>
	<form id="form" action="#" method="post" >
		<div class="pc_user_about">
			<div class="recharge-content-top content-public clearfix">
				<div class="left">
					<span><?=_('提取余额到银行卡')?></span>
				</div>
				<div class="right">
					<div class="mg clearfix">
						<span class="onright"><a target="_blank" href="./index.php?ctl=Info&met=recordlist&type=4&typ=e"><?=_('提现记录')?></a></span>
					</div>
				</div>
			</div>
		</div>
		<div class="withdrawals-content-center content-public clearfix">
			<ul class="ulcheak clearfix">
				<?php foreach($service_fee_list as $key => $val){?>
					<li class="<?php if($key == 0){?>underline-gray <?php }?>">
						<input type="hidden" value="<?=($val['id'])?>"  class="service_fee_id">
						<input type="hidden" value="<?=($val['fee_rates']/100)?>"  class="service_fee_rates">
						<input type="hidden" value="<?=($val['fee_min'])?>"  class="fee_min">
						<input type="hidden" value="<?=($val['fee_max'])?>"  class="fee_max">
						<a>
							<p class="bigfont"><?=($val['name'])?></p>
							<p class="smallfont"><?=_('（')?><?=($val['fee_rates'])?><?=_('%服务费）')?></p>
						</a>
						<div></div>
					</li>
				<?php }?>
			</ul>
		</div>

		<div class="withdrawals-content-bottom content-public clearfix">
			<div class="left clearfix">
				<div class="leftauto">
					<div>
						<dl class="clearfix">
							<dt>
								<?=_('收款方：')?>
							</dt>
							<dd style="display:inline-block;width:75%;">
								<input type="text" class="text text-4" name="bank_name" id="bank_name" placeholder="<?=_('开户人姓名')?>" />
								<input type="text" class="text text-4" name="bank" id="bank" placeholder="<?=_('输入银行')?>" />
								<input type="text" class="text text-4" name="cardno" id="cardno"  placeholder="<?=_('输入银行卡号')?>" />
							</dd>
						</dl>
					</div>
					<div>
						<dl class="clearfix">
							<dt><?=_('提取金额：')?></dt>
							<dd id="width">
								<em class="symbol">￥</em><input type="text" AUTOCOMPLETE="off" class="text text-2" maxlength="10" name="withdraw_money" id="withdraw_money" onKeyUp="amount(this)" onblur="checkMoney(this)" />
								<p class="error_msg" id="error_msg_money"></p>
							</dd>
						</dl>
					</div>
					<div>
						<dl class="clearfix">
							<dt style="float: left;"><?=_('服务费：')?></dt>
							<dd style="line-height:40px;"><em>￥</em><b class="service_total" name="service_total">0.00</b><?=_('（付款总额')?><b class="acount_total" name="acount_total">0.00</b><?=_('）')?></dd>
						</dl>
					</div>
					<div>
						<dl class="clearfix">
							<dt><?=_('提取说明：')?></dt>
							<dd>
								<input type="text" name="con" id="con" class="text text-5 "> </dd>
						</dl>
					</div>
					<div>
						<dl class="clearfix ">
							<dt>手机：</dt>
							<dd><input type="text" class="text text-6" readonly="readonly" name="mobile" id="mobile" style="border:none;"  value="<?=substr($this->user_info['user_mobile'],0,3).'***'.substr($this->user_info['user_mobile'],-3,3)?>"/></dd>
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
							<dt><em style="color: #f00;">*</em>验证码：</dt>
							<dd>
								<input type="text" name="yzm" id="yzm" class="text w60" value="" style="vertical-align:top;"  />
								<input type="button" class="send" data-type="mobile" value="获取手机验证码" />
							</dd>
						</dl>
					</div>
					<div>
						<dl class="clearfix">
							<dt><?=_('确认支付密码:')?></dt>
							<dd>
								<input type="password" name="password" id="password" class="text text-5 ">
							</dd>
						</dl>
					</div>
				</div>
			</div>
			<div class="right clearfix">
				<div class="frame">
					<span class="onright" ><?=_('服务费标准')?></span>
					<table class="statisticaltable" cellspacing="0">
						<tr>
							<th><?=_('到账时间')?></th>
							<th><?=_('服务费率')?></th>
							<th><?=_('服务费下限')?></th>
							<th><?=_('服务费上限')?></th>
						</tr>
						<?php foreach($service_fee_list as $key => $val){?>
							<tr>
								<td><?=($val['name'])?></td>
								<td><?=($val['fee_rates'])?>%</td>
								<td><?=($val['fee_min'])?>/<?=_('笔')?></td>
								<td><?=($val['fee_max'])?>/<?=_('笔')?></td>
							</tr>
						<?php }?>
					</table>
				</div>
			</div>
		</div>
		<div>
			<div class="pc_trans_btn">
				<!--<a onclick="checkInfo()" class="btn btn_active"><?/*=_('提交申请')*/?></a>-->
				<input type="submit" class="save btn_active" value="<?=('提交')?>" />
			</div>
		</div>
	</form>

	<script>


		//点击验证码
    function get_randfunc(obj)
    {
        var sj = new Date();
        url = obj.src;
        obj.src = url + '?' + sj;
    }

		var real = <?=$real?>;

		var checkY = 0;
		// $(document).on("click",".ulcheak li",function(e){
		// 	alert("222");
		// 	$(this).parent().find(".underline-gray").removeClass("underline-gray");
		// 	$(this).addClass("underline-gray");
		// })
		$(".ulcheak li").click(function(){
			// alert("7777");
			$(this).parent().find(".underline-gray").removeClass("underline-gray");
			$(this).addClass("underline-gray");

			count();
		});

		function count()
		{
			//计算服务费与付款总额
			var money = $("#withdraw_money").val();
			if(!money)
			{
				money = 0;
			}

			var seriver_fee = Number(money * ($(".underline-gray").find(".service_fee_rates").val()*1)).toFixed(2);
			var fee_min = $(".underline-gray").find(".fee_min").val();
			var fee_max = $(".underline-gray").find(".fee_max").val();
			if(seriver_fee*1 > fee_max*1){
				seriver_fee = fee_max;
			}else if(seriver_fee*1 < fee_min*1){
				seriver_fee = fee_min;
			}else{
				seriver_fee = seriver_fee;
			}

			var	acount_total = Number(seriver_fee*1+money*1).toFixed(2);
			$(".service_total").html(seriver_fee);
			$(".acount_total").html(acount_total)
		}

		//验证提现金额是否大于当前账户的余额
		function checkMoney(e)
		{
			$.post("./index.php?ctl=Info&met=getUserResourceInfo&typ=json",
				function(data){

					if(data.status == 200)
					{
						user_resource = data.data.user_money;

						if(Number(user_resource) < Number($(e).val()))
						{
							str = '您的余额只有' + user_resource + '元。';
							$("#withdraw_money").val("");
							$(e).parent().find(".error_msg").html(str);
						}

						count();
					}
				});


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
				var val = <?=$this->user_info['user_mobile']?>
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
			//var val = eval(type);
			var val = '';
			if(type == 'mobile')
			{
				val  = <?=$this->user_info['user_mobile']?>;
			}

			var obj = $(".send");
			if(yzm == ''){
				obj.addClass('red');
				$("<label class='error red ml4'>"+icon+"<?=_('请填写验证码')?></label>").insertAfter(obj);
				return false;
			}

			var url = SITE_URL +'?ctl=Info&met=checkYzm&typ=json';
			var pars = 'yzm=' + yzm +'&type='+type +'&val='+val;
			$.post(url, pars, function(a){
				flag = false;
				if (a.status == 200)
				{
					flag = true;

					checkY = 1;
				}
				else
				{
					obj.addClass('red');
					$("<label class='error red ml4'>"+icon+"<?=_('验证码错误')?></label>").insertAfter(obj);

					checkY = 0;
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
			//暂时不需要判断用户是不是实名认证
			rules:{
//				checkName:function()
//				{
//					if(!real)
//					{
//						return '<a href="./index.php?ctl=Info&met=certification">请先去实名认证</a>';
//					}else
//					{
//						if($("#bank_name").val() !== '<?//=$realname?>//')
//						{
//							return '该账户不是实名认证用户名';
//						}
//					}
//					console.info($("#bank_name").val());
//				}

				checkCardno:function()
				{

					if($('#cardno').val().length<16 ||$('#cardno').val().length >19) {
						return '银行卡号格式错误';
					}

				}
			},
			fields: {
				'bank_name': 'required;checkName',
				'bank': 'required;',
				'cardno': 'required;checkCardno',
				'withdraw_money': 'required;',
				'password':'required',
			},
			valid:function(form){
				var id        = $(".underline-gray").find(".service_fee_id").val();
				var bank_name = $("#bank_name").val();
				var cardno    = $("#cardno").val();
				var bank      = $("#bank").val();
				var withdraw_money = $("#withdraw_money").val();
				var con       = $("#con").val();
				var paypasswd = $("#password").val();
				var mobile = $("#mobile").val();
				var val = checkY;
				var yzm = $("#yzm").val();
				var me = this;



				// 提交表单之前，hold住表单，防止重复提交
				me.holdSubmit();
				var ajax_url = '<?= Yf_Registry::get('url');?>?ctl=Info&met=addWithdraw&typ=json';
				//表单验证通过，提交表单
				$.ajax({
					url: ajax_url,
					data:{id:id,bank_name:bank_name,cardno:cardno,bank:bank,withdraw_money:withdraw_money,con:con,paypasswd:paypasswd,yzm:yzm,mobile:mobile,val:val},
					success:function(a){
						console.info(a);
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
					function ()
				{
					me.holdSubmit(false);
				}

			});
		}

		});
	</script>
<?php }else{?>
	<div class="security-tips">提现前必须先进行手机绑定，点击这里进行<a href="<?=Yf_Registry::get('ucenter_api_url')?>?ctl=User&met=security&op=mobile">手机绑定</a></div>
<?php }?>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>