<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
          <link href="<?= $this->view->css ?>/seller_conter.css?ver=<?=VER?>" rel="stylesheet">
          
        <div class="tabmenu">
            <ul>
                    <li ><a href="./index.php?ctl=Seller_Shop_Entityshop&met=entityShop&typ=e"><?=__('地图显示')?></a></li>
                   
                    <li class="active bbc_seller_bg"><a href="./index.php?ctl=Seller_Shop_Entityshop&met=entityShop&typ=e&act=list"><?=__('列表显示')?></a></li>
               
                 
            </ul>
           </div>    
         <form id="form" action="./index.php?ctl=Seller_Shop_Supplier&met=delSupplier" method="post">
        <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <th class="tl"><?=__('实体店铺名称')?></th>
            <th ><?=__('电话')?></th>
            <th ><?=__('详细地址')?></th>
            <th width="320"><?=__('公交')?></th>
            <th width="120"><?=__('操作')?></th>
        </tr>
        <?php if($data['items']) {
                    foreach ($data['items'] as $key => $value){ ?>
        <tr class="row_line">
          
        
            <td class="tl"><span class="number"><?= $value['entity_name'] ?></span></td>
            <td><span class="number"><?= $value['entity_tel'] ?></span></td>
            <td><span class="number"><?= $value['entity_xxaddr'] ?></span></td>
            <td><span class="number"><?= $value['entity_transit'] ?></span></td>
            <td width="120" class="nscs-table-handle">
                <span class="edit"><a class="edit_map" data-id="<?= $value['entity_id'] ?>"><i class="iconfont icon-zhifutijiao"></i><?=__('编辑')?></a></span>
                <span class="del"><a data-param="{'ctl':'Seller_Shop_Entityshop','met':'delEntity','id':'<?= $value['entity_id'] ?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
            </td>
        </tr>
     
       <?php }}else{?>
        <tr class="row_line">
                <td colspan="99">
                    <div class="no_account">
                        <img src="<?=$this->view->img?>/ico_none.png">
                        <p><?=__('暂无符合条件的数据记录')?></p>
                    </div>
                </td>
            </tr>
     <?php }?>
            <!--- 分页 --->
        <?php if(!empty($page_nav)){?>
	<tr>
            <td colspan="99">
		<div class="page">
			<?=$page_nav?>
		</div>
	    </td>
	</tr>
        <?php } ?>
        </table>
        </form>
          
          <link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
        <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
          <script>
        $('.edit_map').click(function ()
        {
            var entity_id = $(this).attr("data-id");
            $.dialog({
                title: "<?=__('编辑实体店铺')?>",
                content: 'url: ' + SITE_URL + '?ctl=Seller_Shop_Entityshop&met=editEntityInfo&typ=e&entity_id='+entity_id,
                width: 600,
                height: 450,
                max: !1,
                min: !1,
                cache: !1,
                lock: !0
            });

        });
          </script>  
 <?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>
