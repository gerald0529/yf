<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>

    <div class="aright">
        <div class="member_infor_content">
            <div class="order_content" style="min-height: 0!important;">
        <div class="div_head tabmenu clearfix">
            <ul class="tab clearfix">
                <li  class="active"><a href="javascript:void(0);" ><?=__('平台红包')?></a></li>
            </ul>
        </div>
        <div style="margin-top: 10px;" class="clearfix">
            <form id="search_form" method="get">
                <input type="hidden" name="ctl" value="Buyer_RedPacket"/>
                <input type="hidden" name="met" value="redPacket"/>
                <p class="pright" style=" float: right;margin-left: 10px;font-size: 12px;}">
                    <select name="state">
                        <option value="" <?php if(!request_int('state')){echo "selected";}?>><?=__('选择状态')?></option>
                        <option value="<?=RedPacket_BaseModel::UNUSED?>" <?=RedPacket_BaseModel::UNUSED == request_int('state')? "selected":""?>><?=__(RedPacket_BaseModel::$redpacketState[RedPacket_BaseModel::UNUSED])?></option>
                        <option value="<?=RedPacket_BaseModel::USED?>" <?=RedPacket_BaseModel::USED == request_int('state')? "selected":""?>><?=__(RedPacket_BaseModel::$redpacketState[RedPacket_BaseModel::USED])?></option>
                        <option value="<?=RedPacket_BaseModel::EXPIRED?>" <?=RedPacket_BaseModel::EXPIRED == request_int('state')? "selected":""?>><?=__(RedPacket_BaseModel::$redpacketState[RedPacket_BaseModel::EXPIRED])?></option>
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
    <table class="ncm-default-table annoc_con">
        <thead>
        <tr class="bortop">

            <th class="w150"><?=__('面额')?></th>
            <th class="tl opti"><?=__('有效期')?></th>
            <th class="tl opti"><?=__('领取时间')?></th>
            <th class="tl opti"><?=__('使用条件')?></th>
            <th class="w120"><?=__('状态')?></th>
            <th class="w110"><?=__('操作')?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($data['items'])){ ?>
        <?php foreach($data['items'] as $key=>$val){?>
        <tr class="bd-line">

            <td><?=format_money($val['redpacket_price'])?></td>
            <td class="tl opti"><?=$val['redpacket_start_date']?>--<?=$val['redpacket_end_date']?></td>
            <td class="tl opti"><?=$val['redpacket_active_date']?></td>
            <td class="tl opti"><?=__('满')?>&nbsp;<?=format_money($val['redpacket_t_orderlimit'])?>&nbsp;<?=__('可用')?></td>
            <td><?=$val['redpacket_state_label']?></td>
            <td class="ncm-table-handle">
            <?php if($val['redpacket_state_label'] == '未用'){?><span><a class="btn-grapefruit"  href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goodslist&typ=e"><?=__('去使用')?></a></span><?php }else{?><span class="del"></span><?php }?>
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
    <?php if($page_nav){ ?>
        <div style="clear:both"></div><div class="page page_front"><?=$page_nav?></div><div style="clear:both"></div>
    <?php } ?>
    </div>
                    <div style="clear:both"></div>
                    </div>
               </div>
            </div>
        </div>


<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>