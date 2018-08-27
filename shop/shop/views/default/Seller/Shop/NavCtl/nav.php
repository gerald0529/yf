<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
  

	<div class="tabmenu">
		<!-- <ul>
        	<li class="active bbc_seller_bg"><a href="./index.php?ctl=Seller_Shop_Nav&met=nav&typ=e"><?=__('店铺导航')?></a></li>

        </ul> -->
        <a class="button add button_blue bbc_seller_btns" href="./index.php?ctl=Seller_Shop_Nav&met=nav&act=add&typ=e" style="top:-33px;"><i class="iconfont icon-jia"></i><?=__('添加导航')?></a>

        </div>

        <form id="form" action="./index.php?ctl=Seller_Transport&met=delTransport" method="post">
        <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <th width="50"><?=__('排序')?></th>
            <th ><?=__('导航名称')?></th>
            <th width="200"><?=__('是否显示')?></th>
            <th width="120"><?=__('操作')?></th>
        </tr>
       <?php if($data['items']) {
                    foreach ($data['items'] as $key => $value){ ?>
        <tr class="row_line">
          
        
            <td><span class="number"><?=$value['displayorder']?></span></td>
            <td><span class="number"><?=$value['title']?></span></td>
            <td><span class="number"><?=$value['nav_status_cha']?></span></td>
       
            <td class="nscs-table-handle">
                <span class="edit"><a href="./index.php?ctl=Seller_Shop_Nav&met=nav&act=edit&nav_id=<?=$value['id']?>&typ=e"><i class="iconfont icon-zhifutijiao"></i><?=__('编辑')?></a></span>
                <span class="del"><a data-param="{'ctl':'Seller_Shop_Nav','met':'delNav','id':'<?=$value['id']?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
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
                        <p><?=__('暂无符合条件的数据记录')?></p>
                    </div>
                </td>
            </tr>
     <?php } ?>

        </table>
        </form>

    
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

