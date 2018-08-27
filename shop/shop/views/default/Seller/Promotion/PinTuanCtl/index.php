<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.dialog.js" charset="utf-8"></script>

<div class="exchange">
	<!-- <div class="alert">
        <?php if($shop_type){ ?>
            <ul>
                <li>1、<?=__('点击添加活动按钮可以添加拼团活动，点击编辑按钮可以对拼团活动进行编辑')?></li>
                <li>2、<?=__('点击删除按钮可以删除拼团活动')?></li>
            </ul>
        <?php  }else{ ?>
            <h4>
                <?php if($com_flag){ ?><?=__('套餐过期时间')?>：<em class="red"></em><?=$combo_row['combo_end_time']?>。
                <?php }else{ ?>
                    <?=__('你还没有购买套餐或套餐已经过期，请购买或续费套餐')?>
                <?php  } ?>
            </h4>
            <ul>
                <li>1、<?=__('点击套餐管理可以购买或续费套餐')?></li>
                <li>2、<?=__('点击添加活动按钮可以添加拼团活动，点击编辑按钮可以对拼团活动进行编辑')?></li>
                <li>3、<?=__('点击删除按钮可以删除拼团活动')?></li>
                <li>4、<strong  class="bbc_seller_color"><?=__('相关费用会在店铺的账期结算中扣除')?></strong></li>
            </ul>
        <?php } ?>
	</div> -->
	<div class="search fn-clear">
	<form id="search_form" method="get" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_PinTuan&met=index&typ=e">
        <input type="hidden" name="ctl" value="Seller_Promotion_PinTuan">
        <input type="hidden" name="met" value="index">
        <a class="button refresh" href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_PinTuan&met=index&typ=e"><i class="iconfont icon-huanyipi"></i></a>
        <a class="button btn_search_goods" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
    
        <select name="status" style="margin-right: 5px;">
            <option value=""><?=__('请选择活动状态')?></option>
            <option value="<?=PinTuan_Base::$statusEnabled?>" <?=PinTuan_Base::$statusEnabled == request_string('status')?'selected':''?>><?=__('可用')?></option>
            <option value="<?=PinTuan_Base::$statusDisabled?>" <?='0' === request_string('status')?'selected':''?>><?=__('不可用')?></option>
		</select>
        <input type="text" name="keyword" class="text w200" placeholder="<?=__('请输入拼团名称')?>" value="<?=request_string('keyword')?>" />
	</form>
	<script type="text/javascript">
	$(".search").on("click","a.button",function(){
		$("#search_form").submit();
	});
	</script>
	</div>

	<table class="table-list-style" id="table_list" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<th class="tl" width="200"><?=__('活动名称')?></th>
			<th width="100"><?=__('开始时间')?></th>
			<th width="100"><?=__('结束时间')?></th>
			<th width="80"><?=__('状态')?></th>
			<th width="70"><?=__('操作')?></th>
		</tr>
        <?php
        if($data['items'])
        {
            foreach($data['items'] as $key=>$value)
            {
        ?>
        <tr class="row_line">
            <td class="tl"><?=@$value['name']?></td>
            <td><?=@$value['start_time']?></td>
            <td><?=@$value['end_time']?></td>
            <td><?=@$value['status'] == 1?'可用':'不可用' ?></td>
            <td>
                <span class="edit"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_PinTuan&met=index&op=detail&id=<?=$value['id']?>&typ=e"><i class="iconfont icon-btnclassify2"></i><?=__('详情')?></a></span>
                <?php if( date('Y-m-d H:i:s',time()) < $value['start_time'] || date('Y-m-d H:i:s',time()) > $value['end_time'] ){ ?>
                    <span class="del"><a data-param="{'ctl':'Seller_Promotion_PinTuan','met':'removePinTuanAct','id':'<?=@$value['id']?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>

                <?php } ?>
            </td>
        </tr>
        <?php }  }else{ ?>
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



