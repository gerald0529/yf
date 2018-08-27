<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
 
	<div class="aright">
        <div class="member_infor_content">
			<div class="div_head tabmenu clearfix">
				<ul class="tab clearfix">
					<li  class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Fenxiao&met=FenxiaoList"><?=__('我的推广')?></a></li>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Fenxiao&met=FenxiaoOrder"><?=__('我的业绩')?></a></li>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Fenxiao&met=FenxiaoCommission"><?=__('佣金记录')?></a></li>
				</ul>
			</div>
			
			<div class="order_content_title clearfix">
				<div style="margin-top: 10px;" class="clearfix">
					<form id="search_form" method="get">
						<input name="ctl" value="Fenxiao" type="hidden">
						<input name="met" value="FenxiaoList" type="hidden">
						<p class="pright">
							<span style="line-height: 25px;margin-left: 8px;"></span><input name="userName" class="A" style=" margin-left: 2px;width: 150px;" value="<?=request_string('userName')?>" placeholder="<?=__('请输入会员名称')?>" type="text"> <a href="javascript:void(0);" class="sous" style="margin-right: 0;"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
						</p>
						<div style="clear:both;"></div><p></p>
					</form>
				</div>
			</div>
			
			<table class="ncm-default-table annoc_con">
				<thead>
					<tr class="bortop">
						<th class="w150"><?=__('用户名称')?></th>
						<th class="w110"><?=__('性别')?></th>
						<th class="w110"><?=__('手机号码')?></th>
						<th class="w120"><?=__('推广会员数')?></th>
						<th class="w120"><?=__('消费总额')?></th>
						<th class="w110"><?=__('带来佣金')?></th>
						<th class="w150"><?=__('注册时间')?></th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($data['data'])){?>
					<?php foreach($data['data'] as $key=>$val){?>
					<tr class="bd-line">
						<td style="text-align:left;">
							<img style="width:60px;height:60px;padding:0px 10px;" src="<?=$val['user_logo']?>">
							<?=($val['user_name'])?>
						</td>
                        <td><?php if($val['user_sex'] == 0){echo __('保密');}elseif($val['user_sex'] == 1){echo __('男');}elseif($val['user_sex'] == 2){echo __('女');}?></td>
						<td><?=$val['user_mobile']?></td>
						<td><?=$val['fx_user_count']?></td>
						<td><?=format_money($val['expends'])?></td>
						<td><?=format_money($val['commission'])?></td>
						<td class="ncm-table-handle"><?=$val['user_regtime']?></td>				   
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
			<div class="flip page clearfix"><p><?=$data['pager']?></p></div>
		</div>
    </div>
	<script>
		$(".sous").on("click", function ()
        {
           $("#search_form").submit();
        });
	</script>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>