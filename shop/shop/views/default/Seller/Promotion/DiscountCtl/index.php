<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.dialog.js" charset="utf-8"></script>

<div class="exchange">
	<div class="alert">
        <?php  if($shop_type){ ?>
        <ul>
            <li><?=__('1、点击添加活动按钮可以添加限时折扣活动，点击管理按钮可以对限时折扣活动内的商品进行管理')?></li>
            <li><?=__('2、点击删除按钮可以删除限时折扣活动')?></li>
        </ul>
        <?php }else{ ?>
		<h4>
            <?php if($com_flag){ ?><?=__('套餐过期时间')?>：<em class="red"></em><?=$combo_row['combo_end_time']?>。
            <?php }else{ ?>
                <?=__('你还没有购买套餐或套餐已经过期，请购买或续费套餐')?>
            <?php  } ?>
        </h4>
        <ul>
            <li><?=__('1、点击购买套餐和套餐续费按钮可以购买或续费套餐')?></li>
            <li><?=__('2、点击添加活动按钮可以添加限时折扣活动，点击管理按钮可以对限时折扣活动内的商品进行管理')?></li>
            <li><?=__('3、点击删除按钮可以删除限时折扣活动')?></li>
            <li>4、<strong class="bbc_seller_color"><?=__('相关费用会在店铺的账期结算中扣除')?></strong>。</li>
        </ul>
        <?php } ?>
	</div>

	<div class="search fn-clear">
	<form id="search_form" method="get" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Discount&met=index&typ=e">
        <input type="hidden" name="ctl" value="<?=request_string('ctl')?>">
        <input type="hidden" name="met" value="<?=request_string('met')?>">
        <input type="hidden" name="typ" value="<?=request_string('typ')?>">
        <a class="button refresh" href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Discount&met=index&typ=e"><i class="iconfont icon-huanyipi"></i></a>
        <a class="button btn_search_goods" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
        <input type="text" name="keyword" class="text w200" placeholder="<?=__('请输入活动名称')?>" value="<?=request_string('keyword')?>" />
        <select name="state">
            <option value="0">全部</option>
            <option value="<?=Discount_BaseModel::NORMAL?>" <?=Discount_BaseModel::NORMAL == request_int('state')?'selected':''?> ><?=Discount_BaseModel::$state_array_map[Discount_BaseModel::NORMAL]?></option>
            <option value="<?=Discount_BaseModel::END?>" <?=Discount_BaseModel::END == request_int('state')?'selected':''?>><?=Discount_BaseModel::$state_array_map[Discount_BaseModel::END]?></option>
            <option value="<?=Discount_BaseModel::CANCEL?>" <?=Discount_BaseModel::CANCEL == request_int('state')?'selected':''?>><?=Discount_BaseModel::$state_array_map[Discount_BaseModel::CANCEL]?></option>
        </select>
	</form>
	<script type="text/javascript">
	$(".search").on("click","a.button",function(){
		$("#search_form").submit();
	});
	</script>
	</div>

	<table class="table-list-style table-promotion-list" id="table_list" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<th class="tl" width="200"><?=__('活动名称')?></th>
			<th width="120"><?=__('开始时间')?></th>
			<th width="120"><?=__('结束时间')?></th>
			<th width="50"><?=__('购买下限')?></th>
			<th width="50"><?=__('状态')?></th>
			<th width="120"><?=__('操作')?></th>
		</tr>
        <?php
        if($data['items'])
        {
            foreach($data['items'] as $key=>$value)
            {
        ?>
        <tr class="row_line">
            <td class="tl"><?=$value['discount_name']?></td>
            <td><?=$value['discount_start_time']?></td>
            <td><?=$value['discount_end_time']?></td>
            <td><?=$value['discount_lower_limit']?></td>
            <td><?=$value['discount_state_label']?></td>
            <td class="nscs-table-handle">
                <?php if($value['discount_state_label'] !="已结束"){?>
                <span class="edit"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Discount&met=add&op=edit&typ=e&id=<?=$value['discount_id']?>"><i class="iconfont icon-zhifutijiao"></i><?=__('编辑')?></a></span>
                <span class="edit del_line"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Discount&met=index&op=manage&typ=e&id=<?=$value['discount_id']?>"><i class="iconfont icon-setting"></i><?=__('管理')?></a></span>
                <?php } ?>
                <span class="del"><a data-param="{'ctl':'Seller_Promotion_Discount','met':'removeDiscountAct','id':'<?=$value['discount_id']?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
            </td>
        </tr>
        <?php
            }
        }
        else
        {
        ?>
        <tr class="row_line">
            <td colspan="99">
                <div class="no_account">
                    <img src="<?=$this->view->img?>/ico_none.png">
                    <p>暂无符合条件的数据记录</p>
                </div>
            </td>
        </tr>
        <?php } ?>
	</table>
    <?php if($page_nav){ ?>
        <div class="mm">
            <div class="page"><?=$page_nav?></div>
        </div>
    <?php }?>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>



