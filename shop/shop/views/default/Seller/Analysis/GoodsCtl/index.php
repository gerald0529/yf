<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/seller_center.css"/>

<div id="mainContent">
    <div class="fl mr50" style="width: 100%;">
        <table class="ncsc-default-table">
            <thead>
            <tr class="sortbar-array">
                <th class="align-center"><?=__('商品名称')?></th>
                <th class="align-center"><?=__('价格')?></th>
                <th class="align-center"><?=__('近30天下单商品数')?></th>
                <th class="align-center"><?=__('近30天下单金额')?></th>
            </tr>
            </thead>
            <tbody id="datatable">
            <?php if (empty($data))
            { ?>
                <tr>
                    <td colspan="20" class="norecord">
                        <div class="no_account"> <img src="<?=$this->view->img?>/ico_none.png"><p><?=__('暂无符合条件的数据记录')?></p></div>
                    </td>
                </tr>
            <?php }
            else
            {
                foreach ($data as $k => $v)
                {
                    ?>
                    <tr>
                        <td class="align-center"><?= $v['goods_name'] ?></td>
                        <td class="align-center"><?= $v['goods_price'] ?></td>
                        <td class="align-center"><?= $v['nums'] ?></td>
                        <td class="align-center"><?= $v['cashes'] ?></td>
                    </tr>
                <?php }
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="h30 cb">&nbsp;</div>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

