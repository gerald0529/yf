<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

			<a class="button add button_blue bbc_seller_btns" href="./index.php?ctl=Seller_Supplier_Distributor&met=addGrade&typ=e" style="line-height: 35px;margin-left: 92%;"><i class="iconfont icon-jia"></i><?=__('添加等级')?></a>
        <form id="form" action="./index.php?ctl=Seller_Transport&met=delTransport" method="post">
        <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <!--<th width="50"><?=__('等级')?></th>-->
            <th width="100"><?=__('排序')?></th>
            <th width="200"><?=__('等级名称')?></th>
            <th width="tc"><?=__('等级折扣')?></th>
            <th width="150"><?=__('操作')?></th>
        </tr>
       <?php if($data['items']) {
                    foreach ($data['items'] as $key => $value){ ?>
        <tr class="row_line">
            <!--<td><span class="number"><?=$value['distributor_level_id']?></span></td>-->
            <td><span class="number"><?=$value['distributor_leve_order']?></span></td>
            <td><span class="number"><?=$value['distributor_leve_name']?></span></td>
            <td><span class="number"><?=$value['distributor_leve_discount_rate']?> %</span></td>
            <td class="nscs-table-handle">
                <span class="edit"><a href="./index.php?ctl=Seller_Supplier_Distributor&met=addGrade&act=edit&grade_id=<?=$value['distributor_level_id']?>&typ=e"><i class="iconfont icon-zhifutijiao"></i><?=__('编辑')?></a></span>
                <span class="del"><a data-param="{'ctl':'Seller_Supplier_Distributor','met':'delGrade','id':'<?=$value['distributor_level_id']?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
            </td>
        </tr>
        <?php } ?>
        <!--- 分页 --->
        <?php if(!empty($page_nav)){?>
	<tr>
            <td colspan="99">
		<div class="page">
			<?=$page_nav?>
		</div>
	    </td>
	</tr>
        <?php } }else{?>
        <tr class="row_line">
                <td colspan="99">
                    <div class="no_account">
                        <img src="<?=$this->view->img?>/ico_none.png">
                        <p><?=__('无符合条件的数据记录')?>暂</p>
                    </div>
                </td>
            </tr>
     <?php } ?>

        </table>
        </form>
<!--<script type="text/javascript">
	$('.del').click(function (){
		var distributor_level_id = $(this).attr('data-id');
		var act = 'del';
		$.post(SITE_URL  + '?ctl=Seller_Supplier_Distributor&met=editGrade&typ=json',{act:act,distributor_level_id:distributor_level_id},function (redata){
			if(redata.status == 200)
            {
            	Public.tips.success('操作成功！');
                location.href="index.php?ctl=Seller_Supplier_Distributor&met=setGrade";
            }
            else
            {
                   Public.tips.error('操作失败！');
            }
	})
	})
</script>-->
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>