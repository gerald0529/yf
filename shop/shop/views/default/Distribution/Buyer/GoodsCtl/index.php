<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
<style>
	.share-link{width:350px;height:30px;line-height:30px;border:1px solid #ddd;padding-left:5px;}
	.copy-btn{height:18px;line-height:18px;border-radius:0px;vertical-align:top;}
</style>
<style>
.dialog_wrapper {
filter:progid:DXImageTransform.Microsoft.gradient(enabled='true', startColorstr='#3F000000', endColorstr='#3F000000');/* background: rgba(0,0,0,0.25);*/ padding: 5px; }
.dialog_body { background-color: #FFF; border: solid 1px #E6E6E6; }
.dialog_head { background-color: #F5F5F5; margin: 0; border-bottom: solid 1px #E6E6E6; position: relative; z-index: auto; }
.dialog_title { line-height: 20px; display: inline-block; height: 20px; padding: 5px 0 5px 10px; border: none 0; }
.dialog_title_icon { font-size: 14px; line-height: 20px; font-weight: bold; color: #555; }
.dialog_close_button { font-family: Verdana; font-size: 14px; line-height: 20px; font-weight: 700; color: #999; text-align: center; display: block; width: 20px; height: 20px; position: absolute; z-index: 1; top: 5px; right: 5px; cursor: pointer; }
.dialog_close_button:hover { text-decoration: none; color: #333; }
.dialog_loading { text-align: center; }
.dialog_loading_text { padding: 0px; padding-top: 20px; padding-left: 10px; width: 199px; font-style: italic; margin: 15px auto 5px auto; text-align: left; font-size: 15px; background: transparent url(images/loading.gif) no-repeat center top; }
.dialog_message_body { margin: 15px 5px 5px 5px; }
.dialog_message_contents { font-size: 14px; line-height: 24px; color: #000; text-align: center; min-width: 360px; padding: 30px 20px; }
.dialog_message_contents i { display: inline-block; vertical-align: middle; width: 24px; height: 24px; margin-right: 12px; }
.dialog_message_contents i.alert_info { background: transparent url(images/info.gif) no-repeat 0 0; }
.dialog_message_contents i.alert_right { background: transparent url(images/right.gif) no-repeat 0 0; }
.dialog_message_contents i.alert_error { background: transparent url(images/error.gif) no-repeat 0 0; }
.dialog_message_confirm { background: transparent url(images/message_confirm.gif) no-repeat left top; }
.dialog_buttons_bar { text-align: center; padding: 10px 0; margin: 0; border-top: solid 1px #EEE; }
.dialog_buttons_bar input { margin: 0px 5px; }
time.countdown { color: #999; margin: 10px auto; }
time.countdown i { font-size: 14px; margin-right: 4px; }
.dialog_buttons_bar .btn1 { width: 75px; height: 29px; border: 0; background: url(images/btn1.gif); color: #c73702; font-weight: bold; font-size: 14px; cursor: pointer; }
.dialog_buttons_bar .btn2 { width: 75px; height: 29px; border: 0; background: url(images/btn2.gif); color: #4e4e4e; font-weight: bold; font-size: 14px; cursor: pointer; }
.dialog_has_title { background: #555; }
.dialog_has_title .dialog_body { background: #fff; bottom: 3px; right: 3px; padding: 10px; border: 4px solid #D7DCE1; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #666; }
.dialog_has_title .dialog_head { height: 30px; border-bottom: 1px solid #F1F3FA; }
.eject_con h3{text-align:left;margin-top:0px;}
.eject_con .bottom{border-top:0px;}

.normal{height:auto;}
.normal dt,.normal dd{float:left;}
.normal dt{width:100px;text-align:right;height:30px;line-height:30px;}
.normal dd{height:30px;line-height:30px;}
</style>
    <div class="aright">
        <div class="member_infor_content">
			<div class="div_head tabmenu clearfix">
				<ul class="tab clearfix">
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Directseller&met=index&typ=e"><?=__('分销申请')?></a></li>
					<li class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Goods&met=index"><?=__('商品列表')?></a></li>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Directseller&met=directsellerList"><?=__('我的推广')?></a></li>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Directseller&met=directsellerOrder"><?=__('我的业绩')?></a></li>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Directseller&met=directsellerCommission"><?=__('佣金记录')?></a></li>
				</ul>
			</div>
			
			<ul>
				<li>
					<div class="operation">
						<form id="set_name" method="post" action="">
							<div class="search-form">
								<div class="normal">
									<dt><?=__('小店名称：')?></dt>
									<dd>
										<input type="text" class="text w300" name="user_directseller_shop" id="user_directseller_shop" value="<?=@$this->user['info']['user_directseller_shop']?>">
										<a class="bbc_btns ncbtn ncbtn-mint"><?=__('保存修改')?></a>
									</dd>
								</div>
							</div>
						</form>
						<div class="clear"></div>
						<div class="normal" style="height:110px;margin-top:10px;">
							<dt><?=__('小店二维码：')?></dt>
							<dd><img width="110" src='<?php echo Yf_Registry::get('base_url')."/shop/api/qrcode.php?data=".urlencode(Yf_Registry::get('shop_wap_url')."/tmpl/member/directseller_store.html?uid=".Perm::$userId)?>'></dd>
						</div>
					</div>
				</li>
			</ul>
			
			<div class="order_content_title clearfix">
				<div style="margin-top: 10px;" class="clearfix">
					<form id="search_form" method="get">
						<input name="ctl" value="Distribution_Buyer_Goods" type="hidden">
						<input name="met" value="index" type="hidden">
						<p class="pright">
							<span style="line-height: 25px;margin-left: 8px;"></span><input name="keywords" class="A" style=" margin-left: 2px;width: 150px;" value="<?=request_string('keywords')?>" placeholder="<?=__('请输入商品名称')?>" type="text"> <a href="javascript:void(0);" class="sous" style="margin-right: 0;"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
						</p>
						<div style="clear:both;"></div><p></p>
					</form>
				</div>
			</div>
			
			<table class="ncm-default-table annoc_con icos">
				<thead>
					<tr class="bortop">
						<th class=""><?=__('商品名称')?></th>
						<th class="tl opti"><?=__('商品价格')?></th>
						<th class="w150"><?=__('佣金比例')?></th>
						<th class="w150"><?=__('可得佣金')?></th>
						<th class="w150"><?=__('操作')?></th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($data['items'])){ ?>
					<?php foreach($data['items'] as $key=>$val){
							$goods_id = @$val['goods_id']['goods_id']?$val['goods_id']['goods_id']:$val['goods_id'][0]['goods_id'];
							$rec = 'u'.Perm::$userId.'s'.$val['shop_id'].'c'.$val['common_id'];
					?>
					<tr class="bd-line">
						 <td class="order_goods">
                            <img src="<?=image_thumb($val['common_image'],50,50)?>"/>
                            <a target="_blank"  href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($goods_id)?>"><?=($val['common_name'])?></a>
							<a target="_blank" style="color:#666;" href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&typ=e&id=<?=$val['shop_id']?>"><?=__('店铺名称：')?><?=$val['shop_name']?></a>
                        </td>
						<td class="tl opti"><?=format_money($val['common_price'])?></td>
						<td>
							<?=__('一级比例：')?><?=$val['common_cps_rate'].'%'?><br />
							<?=__('二级比例：')?><?=$val['common_second_cps_rate'].'%'?><br />
							<?=__('三级比例：')?><?=$val['common_third_cps_rate'].'%'?>
						</td>
						<td>
							<?=__('一级分佣：')?><?=format_money($val['common_cps_rate']*$val['common_price']/100)?><br />
							<?=__('二级分佣：')?><?=format_money($val['common_second_cps_rate']*$val['common_price']/100)?><br />
							<?=__('三级分佣：')?><?=format_money($val['common_third_cps_rate']*$val['common_price']/100)?>
						</td>

						<td>
							<p>
								<span class="edit"><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Goods&met=editGoods&shop_id=<?=$val['shop_id']?>&common_id=<?=$val['common_id']?>"><i class="iconfont icon-chakan"></i><?=__('编辑')?></a></span>
								<span class="edit"><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($goods_id).'&rec='.$rec?>"><i class="iconfont icon-chakan"></i><?=__('推广')?></a></span>
								<span class="edit">
									<a data-param='<?php echo urlencode(Yf_Registry::get('base_url').'/shop/api/share.php?type=goods&gid='.$goods_id.'&rec='.$rec); ?>' data-url='<?php echo Yf_Registry::get('base_url').'/shop/api/share.php?type=goods&gid='.$goods_id.'&rec='.$rec; ?>' onclick="showLink(this);">
										<i class="iconfont icon-chakan"></i><?=__('分享')?>
									</a>
								</span>
							</p>
						</td>						
					</tr>
				<?php }?>
				<?php }else{ ?>
					<tr id="list_norecord">
						<td colspan="20" class="norecord">
						  <div class="no_account">
							<img src="<?= $this->view->img ?>/ico_none.png"/>
							<p><?=__('暂无符合条件的数据记录')?></p>
						</div>  
						</td>
					</tr>
				<?php } ?>	
				</tbody>
			</table>
			<div class="flip page clearfix"><p><?=$page_nav?></p></div>
		</div>
    </div>
	
	<!--添加小店名称-->
	<div id="dialog_link" class="eject_con" style="display:none;">
		<dl class="invite-form">
			<dt><?=__('推广链接')?>：</dt>
			<dd>
				<input type="text" readonly="" id="share-link" value="" class="share-link">
			<dt><?=__('推广二维码')?>：</dt>
			<dd>
				<img width="200" id='share_code' src="" />
			</dd>
		</dl>
	</div>
	<script>
		$(".sous").on("click", function ()
        {
           $("#search_form").submit();
        });
		
		//分享链接
        function showLink(ele) 
		{
            $('#dialog_link').yf_show_dialog({width: 550, title: "<?=__('分享链接')?>"});
			var val = ele.getAttribute('data-url');
			$('#share-link').val(val);
			var param = ele.getAttribute("data-param");
			var img = '<?=Yf_Registry::get('base_url')?>'+'/shop/api/qrcode.php?data='+param+'';
			$('#share_code').attr('src',img);
        }
		
		$(".ncbtn-mint").on("click",function(){
			$("#set_name").submit();
		});
		
		$(document).ready(function(){
            $('#set_name').validator({
                debug:true,
                ignore: ':hidden',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,

                fields: {
                    'user_directseller_shop': 'required;length[2~30]'
                },
				valid: function(form)
				{
					var me = this;
					// 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
					me.holdSubmit(function(){
						Public.tips.error("<?=__('正在处理中...')?>");
					});
					$.ajax({
						url: "index.php?ctl=Distribution_Buyer_Directseller&met=setShopName&typ=json",
						data: $(form).serialize(),
						type: "POST",
						success:function(e){
							if(e.status == 200)
							{
								Public.tips.success("<?=__('保存成功!')?>");
								location.href="index.php?ctl=Distribution_Buyer_Goods&met=index&typ=e";//成功后跳转
							}
							else
							{
								Public.tips.error("<?=__('保存失败！')?>");
							}
							me.holdSubmit(false);
						}
					});
				}
            });
        });
	</script>
 <!-- 尾部 -->
 <?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>