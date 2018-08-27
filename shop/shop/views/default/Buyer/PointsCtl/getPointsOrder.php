<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
<div class="aright">
	<div class="member_infor_content">
        <div class="order_content">
          <div class="div_head tabmenu clearfix">
            <ul class="tab clearfix">
              <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Points&met=points"><?=__('积分明细')?></a></li>
              <li  class="active"> <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Points&met=points&op=getPointsOrder"><?=__('兑换记录')?></a></li>
            </ul>
          </div>
          <ul>
            <li> 
              <div>
			  <div class="order_content_title clearfix">
				<div style="margin-top: 10px;" class="clearfix">
					<form id="search_form" method="get">
						<input type="hidden" name="ctl" value="Buyer_Points"/>
						<input type="hidden" name="met" value="points"/>
						<input type="hidden" name="op" value="getPointsOrder"/>
						<p class="pright">
							<select name="state">
								<option value="" <?php if($state==''){echo "selected";}?>><?=__('选择状态')?></option>
								<option value="1" <?php if($state==1){echo "selected";}?>><?=__('等待发货')?></option>
								<option value="2" <?php if($state==2){echo "selected";}?>><?=__('等待收货')?></option>
								<option value="3" <?php if($state==3){echo "selected";}?>><?=__('确认收货')?></option>
								<option value="4" <?php if($state==4){echo "selected";}?>><?=__('取消')?></option>
							</select>
							 <a class="btn_search_goods sous" href="javascript:void(0);">
							<i class="iconfont icon-btnsearch  icon_size18"></i><?=__('搜索')?></a></p>
					</form>
				</div>
			</div>
			<script type="text/javascript">
				$(".sous").on("click", function ()
				{
					$("#search_form").submit();
				});
				</script>
                <table  class="ncm-default-table annoc_con">
				<thead>
                  <tr class="bortop">
					  <th> <?=__('订单编号')?> </th>
                      <th  style="width:400px"> <?=__('礼品信息')?> </th>
					  <th> <?=__('积分')?> </th>
					  <th> <?=__('数量')?> </th>
					  <th> <?=__('合计（积分）')?> </th>
					  <th> <?=__('交易状态')?> </th>
					  <th> <?=__('物流单号')?> </th>
					  <th style="width:200px"> <?=__('操作')?> </th>
                  </tr>
				 </thead>
				  <?php if(!empty($data['items'])){ ?>
				  <?php foreach($data['items'] as $key=>$val){?>
				  <?php foreach($val['points_ordergoods_list'] as $k=>$v){?>
                  <tr>
                    <td><?=$val['points_order_id']?></td>
                    <td><a href="<?= Yf_Registry::get('url') ?>?ctl=Points&met=detail&id=<?=$v['points_goodsid']?>" target="_blank"><?=$v['points_goodsname']?></a></td>
					<td><?=$v['points_goodspoints']?></td>
                    <td><?=$v['points_goodsnum']?></td>
                    <td><?=$val['points_allpoints']?></td>
                    <td><?php if($val['points_orderstate'] == '1'){?><?=__('已下单')?><?php }elseif($val['points_orderstate'] == '2'){?><?=__('已发货')?><?php }elseif($val['points_orderstate'] == '3'){?><?=__('完成')?><?php }elseif($val['points_orderstate'] == '4'){?><?=__('取消')?><?php }?></td>



                    <td> <a style="position:relative;" onmouseover="show_logistic('<?=($val['points_order_id'])?>','<?=($val['points_express_id'])?>','<?=($val['points_shippingcode'])?>')" onmouseout="hide_logistic('<?=($val['points_order_id'])?>')">
                            <?=$val['points_shippingcode']?>
                            <div style="display: none;" id="info_<?=($val['points_order_id'])?>" class="prompt-01"> </div>
                        </a>
                    </td>

                    <td><?php if($val['points_orderstate'] == '2'){?><a onclick="confirmPointsOrder('<?=$val['points_order_id']?>')" class="to_views bbc_btns "><i class="iconfont icon-duigou1"></i><?=__('确认收货')?></a><?php }else{?> <a data-dis="1"  class="to_views cgray"><i class="iconfont icon-duigou1"></i><?=__('确认收货')?></a><?php }?></td>
					
                  </tr>
                  <?php }?>
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
                </table>
				<?php if($page_nav){ ?>
					 <div class="page page_front"><?=$page_nav?></div>
				 <?php } ?>
              </div>
            </li>
          </ul>
        </div>
      </div>
      </div>
    </div>
  </div>
<script>
 //确认收货
       window.confirmPointsOrder = function (e)
       {
            url = SITE_URL + '?ctl=Buyer_Points&met=confirmOrder&typ=';

			$.dialog({
				title: "<?=__('确认收货')?>",
				content: 'url: ' + url + 'e&user=buyer',
				data: { order_id: e},
				height: 200,
				width: 400,
				lock: true,
				drag: false,
				ok: function () {

					var form_ser = $(this.content.order_confirm_form).serialize();

					$.post(url + 'json', form_ser, function (data) {
						if ( data.status == 200 ) {
							$.dialog.alert("<?=__('确认收货成功')?>"), window.location.reload();
							return true;
						} else {
							$.dialog.alert("<?=__('确认订单失败')?>");
							return false;
						}
					})
				}
			})
       }
    window.hide_logistic = function (order_id)
	{
		$("#info_"+order_id).hide();
		$("#info_"+order_id).html("");
	}
       window.show_logistic = function (order_id,express_id,shipping_code)
	{
		$("#info_"+order_id).show();
		$.post(BASE_URL + "/shop/api/logistic.php",{"order_id":order_id,"express_id":express_id,"shipping_code":shipping_code} ,function(da) {

			if(da)
			{
				$("#info_"+order_id).html(da);
			}
			else
			{
				$("#info_"+order_id).html('<div class="error_msg"><?=__('接口出现异常')?></div>');
			}

		})
	}
</script>

<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>