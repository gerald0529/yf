<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
<div class="aright">
        <div class="member_infor_content">
        <div class="order_content">
          <div class="div_head tabmenu clearfix">
            <ul class="tab clearfix">
              <li  class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Points&met=points"><?=__('积分明细')?></a></li>
              <li> <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Points&met=points&op=getPointsOrder"><?=__('兑换记录')?></a></li>
            </ul>
          </div>
          <ul>
            <li>
              <div class="operation">
                <p class="p_title"><?=__('积分获得规则：')?></p>
                <div style="margin-top:10px; margin-left:10px;">
                  <p class="p_content"><?=__('成功注册会员；增加')?><span style="color:#F06"><?=$web['points_reg'] ?></span><?=__('积分；
                    会员每天登陆；增加')?><span style="color:#F06"><?=$web['points_login'] ?></span><?=__('积分；评价完成订单；增加')?><span style="color:#F06"><?=$web['points_evaluate'] ?></span> <?=__('积分')?></p>
                  <p class="p_content"><?=__('购物并完成订单后获得订单总价÷')?><?=$web['points_recharge'] ?><?=__('（最高限额不超过')?><?=$web['points_order'] ?><?=('）')?><?=__('积分')?></p>
                  <p class="p_content"><?=__('如订单发生退款、退货等问题时，退货/退款后相应积分不会扣除')?></p>
                </div>
              </div>
              <div>
			  <div class="order_content_title clearfix">
			  <form action="index.php" id="search_form" method="get">
				 <input type="hidden" name="ctl" value="Buyer_Points"/>
				 <input type="hidden" name="met" value="points"/>
				<p class="order_types">
					<?=__('积分总数：')?> <span style="color:#F00"><?=$this->user['points']['user_points'];?></span>
				</p>
				<p style="margin-left: 10px;float:right;" class="ser_p">
					<input type="text" placeholder="<?=__('描述')?>" style=" border:1px solid #e1e1e1;" name="des" value="<?=$des?>"/>
                      <a class="btn_search_goods sous" href="javascript:void(0);">
					<i class="iconfont icon-btnsearch  icon_size18"></i><?=__('搜索')?></a>
				</p>
				<p style="float:right;">
				<select name="class_id" style="border:1px solid #e1e1e1; ">	
				<option value=""><?=__('请选择操作')?></option>
				<?php foreach($classId as $k=>$v){?>
				 <option value="<?=$k?>" <?php if($class_id == $k ){?>selected="selected"<?php }?>><?=__($v)?></option>
				<?php }?>
			  </select>
				</p>
				<p class="order_time" style="float:right;">
					<span></span>
					<input type="text" value="<?=$start_date?>" class="text w70" id="start_date" name="start_date" placeholder="<?=__('开始时间')?>" autocomplete="off">
					<em style="margin-top: 3px;">&nbsp;&ndash; &nbsp;</em>
					<input type="text" value="<?=$end_date?>" class="text w70" id="end_date" name="end_date" autocomplete="off" placeholder="<?=__('结束时间')?>">
					
				</p>

				 <link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
				<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
				<script type="text/javascript">
				$(".sous").on("click", function ()
				{
					$("#search_form").submit();
				});
				$('#start_date').datetimepicker({
				controlType: 'select',
				format: "Y-m-d",
				timepicker: false
				});

				$('#end_date').datetimepicker({
					controlType: 'select',
					format: "Y-m-d",
					timepicker: false
				});
				jQuery(function(){
					 jQuery('#start_date').datetimepicker({
					  format:'Y-m-d',
					  onShow:function( ct ){
					   this.setOptions({
						maxDate:jQuery('#end_date').val()?jQuery('#end_date').val():false
					   })
					  },
					  timepicker:false
					 });
					 jQuery('#end_date').datetimepicker({
					  format:'Y-m-d',
					  onShow:function( ct ){
					   this.setOptions({
						minDate:jQuery('#start_date').val()?jQuery('#start_date').val():false
					   })
					  },
					  timepicker:false
					 });
				});
				</script>
			  </form>
          </div>
              <div>
                <table  class="ncm-default-table annoc_con">
                  <thead>
                  <tr class="bortop">
                    <th></th>
                    <th width=200><?=__('添加时间')?></th>
                    <th width=200><?=__('积分变更')?></th>
                    <th width=200><?=__('操作')?></th>
                    <th><?=__('描述')?></th>
                  </tr>
                  </thead>
				  
				  <?php if(!empty($data['items'])){ ?>
				  <?php foreach($data['items'] as $key=>$val){?>
                  <tr>
                    <td></td>
                    <td><?=$val['points_log_time']?></td>
				  <td><?=$val['points_log_points']?></td>
                    <td><?=$val['class_id']?></td>
                    <td><?=$val['points_log_desc']?></td>
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
				
                </table>
                <div style="clear:both"></div>
				 <?php if($page_nav){ ?>
					 <div class="page page_front"><?=$page_nav?></div>
				 <?php } ?>
              </div>
               <div style="clear:both"></div>
             
            </li>
          </ul>
        </div>
        </div>
      </div>
    </div>
  </div>


<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>