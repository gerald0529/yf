<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

    <div class="tabmenu">
        <ul>
            <li class="active bbc_seller_bg"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Seller_Group&met=groupList&typ=e"><?=__('组列表')?></a></li>
        </ul>
        <a class="button add button_blue bbc_seller_btns" href="<?=Yf_Registry::get('url')?>?ctl=Seller_Seller_Group&met=groupList&act=add&typ=e"><i class="iconfont icon-jia"></i><?=__('添加组')?></a>
    </div>

	<table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
		<tr>
            <th width="50"></td>
			<th class="tl"><?=__('组名')?></th>
			<th width="100"><?=__('操作')?></th>
		</tr>
        <?php
        if($data['items'])
        {
            foreach($data['items'] as $key=>$value)
            {
        ?>
        <tr>
            <td width="50"></td>
            <td class="tl"><?=$value['group_name']?></td>
            <td class="nscs-table-handle">
                <span class="edit"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Seller_Group&met=groupList&act=edit&typ=e&group_id=<?=$value['group_id']?>"><i class="iconfont icon-zhifutijiao"></i><?=__('编辑')?></a></span>
                <span class="del del_line"><a data-param="{'ctl':'Seller_Seller_Group','met':'removeGroup','id':'<?=$value['group_id']?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
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


<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>



