<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
</head>
<body>

<div class="freight">
	<div class="tabmenu">
		<ul>
            <li class="active bbc_seller_bg"><a><?=__('售卖区域模板设置')?></a></li>
        </ul>
        <a class="button add bbc_seller_btns" href="<?=Yf_Registry::get('url')?>?ctl=Seller_Transport&met=tplarea&act=area"><i class="iconfont icon-jia bbc_seller_btns"></i><?=__('添加区域模板')?></a>

    </div>

    <form id="form" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Transport&met=delTransport" method="post">
    <table class="table-list-style" id="table_list" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <th class="tl" width="80"><?=__('模板名称')?></th>
        <th><?=__('售卖区域')?></th>
        <th width="100"><?=__('操作')?></th>
    </tr>
    <?php if($data) {
                    foreach ($data as $key => $value){ ?>
    <tr class="row_line">
		<?php if(mb_strwidth($value['name'], 'utf8')>20)
			 {
				$str = mb_strimwidth($value['name'], 0, 20, '...', 'utf8');
			 }else
			 {
				$str = $value['name'];
			 }
		?>
        <td class="tl"><?=($value['name'])?></td>
        <td><?=$value['area_name']?></td>
        <td>
            <span class="edit"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Transport&met=tplarea&act=area&id=<?=($value['id'])?>"><i class="iconfont icon-zhifutijiao"></i><?=__('编辑')?></a></span>
            
            <span class="del"><a data-param="{'ctl':'Seller_Transport','met':'delArea','id':'<?=$value['id']?>'}" ><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
      
        </td>
    </tr>
    <?php }}else{?>
    <tr>
        <td colspan="99">
            <div class="no_account">
                <img src="<?= $this->view->img ?>/ico_none.png"/>
                <p><?=__('暂无符合条件的数据记录')?></p>
            </div>
        </td>
    </tr>
    <?php }?>
    </table>
    </form>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>