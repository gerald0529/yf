<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="exchange">
	<div class="alert">
        <?php if($shop_type){ ?>
            <ul>
                <li>1、<?=__('手工设置代金券失效后,用户将不能领取该代金券,但是已经领取的代金券仍然可以使用')?></li>
                <li>2、<?=__('代金券模版和已发放的代金券过期后自动失效')?></li>
            </ul>
        <?php  }else{ ?>
		<h4>
            <?php if($com_flag){ ?><?=__('套餐过期时间')?>：<em class="red"></em><?=$combo['combo_end_time']?>。
            <?php }else{ ?>
                <?=__('你还没有购买套餐或套餐已经过期，请购买或续费套餐')?>
            <?php  } ?>
        </h4>
		<ul>
			<li>1、<?=__('手工设置代金券失效后,用户将不能领取该代金券,但是已经领取的代金券仍然可以使用')?></li>
			<li>2、<?=__('代金券模版和已发放的代金券过期后自动失效')?></li>
			<li>3、<strong class="bbc_seller_color"><?=__('相关费用会在店铺的账期结算中扣除')?>。</strong></li>
		</ul>
        <?php } ?>
	</div>

    <div class="fn-clear" style="padding:10px 0 10px;">
        <form method="get" id="search_form" action="index.php" >
            <input type="hidden" name="ctl" value="<?=$_GET['ctl']?>">
            <input type="hidden" name="met" value="<?=$_GET['met']?>">
            <input type="hidden" name="typ" value="e">

            <span>有效期</span><input type="text" autocomplete="off" name="start_date" id="start_date" class="text w70" value="<?=request_string('start_date')?>" placeholder="开始时间"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em>
            &nbsp;-&nbsp;
            <input type="text" autocomplete="off" name="end_date" id="end_date" class="text w70" value="<?=request_string('end_date')?>" placeholder="结束时间"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em>
            <span><?=__('状态')?></span>
            <select name="state">
                <option value=""><?=__('请选择')?></option>、
                <option value="<?=Voucher_TempModel::VALID?>" <?=request_int('state')==Voucher_TempModel::VALID?'selected':''?> ><?=Voucher_TempModel::$voucher_state_map[Voucher_TempModel::VALID]?></option>
                <option value="<?=Voucher_TempModel::INVALID?>" <?=request_int('state')==Voucher_TempModel::INVALID?'selected':''?> ><?=Voucher_TempModel::$voucher_state_map[Voucher_TempModel::INVALID]?></option>
            </select>

            <span><?=__('领取方式')?></span>
            <select name="method">
                 <option value=""><?=__('请选择')?></option>、
                 <option value="<?=Voucher_TempModel::GETBYPOINTS?>" <?=request_int('method')==Voucher_TempModel::GETBYPOINTS?'selected':''?>><?=Voucher_TempModel::$voucher_access_method_map[Voucher_TempModel::GETBYPOINTS]?></option>
                 <option value="<?=Voucher_TempModel::GETFREE?>" <?=request_int('method')==Voucher_TempModel::GETFREE?'selected':''?>><?=Voucher_TempModel::$voucher_access_method_map[Voucher_TempModel::GETFREE]?></option>
            </select>
            <input type="text" name="keyword" class="text w150" placeholder="<?=__('请输入代金券名称')?>" value="<?=request_string('keyword')?>" />
            <label class="search" >&nbsp;&nbsp;
                <a class="button refresh" href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Voucher&met=index&typ=e"><i class="iconfont icon-huanyipi"></i></a>

                <a class="button btn_search_goods"  href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>

            </label>

            <script type="text/javascript">
                $("a.btn_search_goods").on("click",function(){
                    $("#search_form").submit();
                });
            </script>
        </form>
    </div>

	<table class="table-list-style table-promotion-list" id="table_list" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<th class="tc" width="150"><?=__('代金券名称')?></th>
			<th width="100"><?=__('消费金额')?></th>
			<th width="60"><?=__('面额')?></th>
            <th width="100"><?=__('会员级别')?></th>
			<th width="80"><?=__('领取方式')?></th>
			<th width="60"><?=__('状态')?></th>
			<th width="200"><?=__('有效期')?></th>
			<th width="140"><?=__('操作')?></th>
		</tr>
        <?php
        if($data['items'])
        {
            foreach($data['items'] as $key=>$value)
            {
        ?>
        <tr class="row_line">
            <td><?=($value['voucher_t_title'])?></td>
            <td><?=(format_money($value['voucher_t_limit']))?></td>
            <td><?=(format_money($value['voucher_t_price']))?></td>
            <td><?=($value['user_grade']['user_grade_name'])?></td>
            <td><?=($value['voucher_t_access_method_label'])?></td>
            <td><?=($value['voucher_t_state_label'])?></td>
            <td><?=($value['voucher_t_end_date_day'])?></td>
            <td class="nscs-table-handle">
                <?php if($value['voucher_t_state'] === Voucher_TempModel::VALID){ ?>
                    <span class="edit"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Voucher&met=add&op=edit&id=<?=$value['voucher_t_id']?>&typ=e"><i class="iconfont icon-zhifutijiao"></i><?=__('编辑')?></a></span>
                <?php } ?>
				<span class="edit"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Voucher&met=index&op=detail&id=<?=$value['voucher_t_id']?>&typ=e"><i class="iconfont icon-btnclassify2"></i><?=__('详情')?></a></span>
                <span class="del"><a data-param="{'ctl':'Seller_Promotion_Voucher','met':'removeVoucherTemp','id':'<?=@$value['voucher_t_id']?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
            </td>
        </tr>
        <?php } }else{ ?>
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

<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.dialog.js" charset="utf-8"></script>

<script>
$(document).ready(function(){
    $('#start_date').datetimepicker({
        controlType: 'select',
        timepicker:false,
        format:'Y-m-d'
    });

    $('#end_date').datetimepicker({
    controlType: 'select',
    timepicker:false,
    format:'Y-m-d'
    });
});
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>



