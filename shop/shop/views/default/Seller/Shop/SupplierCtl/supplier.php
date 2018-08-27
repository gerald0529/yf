<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
  

	<div class="tabmenu">
		<ul>
        	<li class="active bbc_seller_bg"><a href="./index.php?ctl=Seller_Shop_Supplier&met=supplier&typ=e"><?=__('供货商')?></a></li>

        </ul>
        <a class="button add button_blue bbc_seller_btns" href="./index.php?ctl=Seller_Shop_Supplier&met=supplier&act=add&typ=e"><i class="iconfont icon-jia"></i><?=__('添加供货商')?></a>

        </div>
    
        <div class="search fn-clear">
            <div id="search_form">
                   <form id="form" action="./index.php?ctl=Seller_Shop_Supplier&met=supplier" method="post">

                       <a class="button refresh" href="<?=Yf_Registry::get('url')?>?ctl=Seller_Shop_Supplier&met=supplier&typ=e&"><i class="iconfont icon-huanyipi"></i></a>
                       <a href="javascript:void(0);" class="button btn_search_goods"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
                 <input type="text" value="" placeholder="<?=__('供货商名称')?>" class="text w200" name="supplier_name">


                </form>
                <script type="text/javascript">
                $(".search").on("click","a.button",function(){
                        $("#form").submit();
                });
                </script>
            </div>
        </div>
        <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <th width="50"><?=__('供货商')?></th>
            <th ><?=__('联系人')?></th>
            <th width="200"><?=__('联系电话')?></th>
            <th width="200"><?=__('备注')?></th>
            <th width="120"><?=__('操作')?></th>
        </tr>
        <?php if($data['items']) {
                    foreach ($data['items'] as $key => $value){ ?>
        <tr class="row_line">
          
        
            <td><span class="number"><?= $value['supplier_name'] ?></span></td>
            <td><span class="number"><?= $value['contacts'] ?></span></td>
            <td><span class="number"><?= $value['contacts_tel'] ?></span></td>
            <td><span class="number"><?= $value['remarks'] ?></span></td>
            <td class="nscs-table-handle">
                <span class="edit"><a href="./index.php?ctl=Seller_Shop_Supplier&met=supplier&act=edit&supplier_id=<?= $value['supplier_id'] ?>&typ=e"><i class="iconfont icon-zhifutijiao"></i><?=__('编辑')?></a></span>
                <span class="del"><a data-param="{'ctl':'Seller_Shop_Supplier','met':'delSupplier','id':'<?= $value['supplier_id'] ?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
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

        </table>



    
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

