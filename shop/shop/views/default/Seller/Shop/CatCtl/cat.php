<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
    include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<div class="tabmenu">
    <a class="button add button_blue bbc_seller_btns" href="./index.php?ctl=Seller_Shop_Cat&met=cat&act=add&typ=e" style="top:-33px;">
        <i class="iconfont icon-jia"></i>
        <?= __('添加分类') ?>
    </a>
</div>

<form id="form" action="./index.php?ctl=Seller_Shop_Cat&met=delAllCat&typ=json" method="post" onsubmit="return submitBtn();">
    <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <th class="tl"><label class="checkbox"><input class="checkall" type="checkbox" /></label><?= __('分类名称') ?></th>
            <th width="100"><?= __('排序') ?></th>
            <th width="100"><?= __('是否显示') ?></th>
            <th width="150"><?= __('操作') ?></th>
        </tr>
        <?php if (!empty($data)) {
            foreach ($data as $key => $value) {
        ?>
                <tr class="row_line">
                    <td class="tl fbold">
                        <label class="checkbox"><input class="checkitem" type="checkbox" name="chk[]" value="<?= $value['shop_goods_cat_id'] ?>" /></label><?= $value['shop_goods_cat_name'] ?>
                    </td>
                    <td><?= $value['shop_goods_cat_displayorder'] ?></td>
                    <td><?= $value['shop_goods_cat_statuscha'] ?></td>
                    <td class="nscs-table-handle">
                        <span class="edit">
                            <a href="./index.php?ctl=Seller_Shop_Cat&met=cat&act=edit&pid=<?= $value['shop_goods_cat_id'] ?>&typ=e">
                                <i class="iconfont icon-zhifutijiao"></i>
                                <?= __('编辑') ?>
                            </a>
                        </span>
                        <span class="del_parent">
                            <a data-param="{'ctl':'Seller_Shop_Cat','met':'delCat','id':'<?= $value['shop_goods_cat_id'] ?>'}" href="javascript:void(0)">
                                <i class="iconfont icon-lajitong"></i>
                                <?= __('删除') ?>
                            </a>
                        </span>
                        <span class="del_line">
                            <a href="./index.php?ctl=Seller_Shop_Cat&met=cat&act=add&typ=e&pid=<?= $value['shop_goods_cat_id'] ?>">
                                <i class="iconfont  icon-jia"></i>
                                <?= __('下级') ?>
                            </a>
                        </span>
                    </td>
                </tr>
                <?php if (!empty($value['subclass'])) {
                    foreach ($value['subclass'] as $keys => $values) {
                ?>
                        <tr class="row_line row_line_dash">
                            <td class="tl">
                                <label class="checkbox">
                                    <input class="checkitem" type="checkbox" name="chk[]" value="<?= $values['shop_goods_cat_id'] ?>" />
                                </label>
                                <span class="span_speical">
                                    <em>●</em>
                                    <?= $values['shop_goods_cat_name'] ?>
                                </span>
                            </td>
                            <td><?= $values['shop_goods_cat_displayorder'] ?></td>
                            <td><?= $values['shop_goods_cat_statuscha'] ?></td>
                            <td class="nscs-table-handle" style="text-align: right;">
                                <span class="edit">
                                    <a href="./index.php?ctl=Seller_Shop_Cat&met=cat&act=edit&pid=<?= $values['shop_goods_cat_id'] ?>&typ=e">
                                        <i class="iconfont icon-zhifutijiao"></i>
                                        <?= __('编辑') ?>
                                    </a>
                                </span>
                                <span class="del">
                                    <a data-param="{'ctl':'Seller_Shop_Cat','met':'delCat','id':'<?= $values['shop_goods_cat_id'] ?>'}" href="javascript:void(0)">
                                        <i class="iconfont icon-lajitong"></i>
                                        <?= __('删除') ?>
                                    </a>
                                </span>
                            </td>
                        </tr>
                    <?php } // endforeach ?>
                <?php } //endif ?>
            <?php } //endforeach?>
            <!--- 分页 --->
            <?php } else { ?>
                <tr class="row_line">
                    <td colspan="99">
                        <div class="no_account">
                            <img src="<?= $this->view->img ?>/ico_none.png">
                            <p><?= __('暂无符合条件的数据记录') ?></p>
                        </div>
                    </td>
                </tr>
        <?php } // endifelse ?>
        <tr>
            <td class="toolBar" colspan="99">
                <input type="hidden" name="op" value="del" />
                <label class="checkbox"><input class="checkall" type="checkbox" /></label><?= __('全选') ?>
                <span>|</span>
                <label class="del">
                    <a data-param="{'ctl':'Seller_Shop_Cat','met':'delAllCat'}" href="javascript:void(0)">
                        <i class="iconfont icon-lajitong"></i><?= __('删除') ?>
                    </a>
                </label>
            </td>
        </tr>
    
    </table>
</form>

<script>
    //============删除操作========
    $('span.del_parent a').click(function () {
        var e = $(this);
        var data_str = e.attr('data-param');
        eval("data_str = " + data_str);
        // console.log(data_str);
        $.dialog.confirm('<?=__('删除一级分类，下面的子类也会删除，您确定要删除吗？')?>',function () {
            $.post(SITE_URL + '?ctl=' + data_str.ctl + '&met=' + data_str.met + '&typ=json', {id: data_str.id}, function (data) {
                // alert(JSON.stringify(data));
                if (data && 200 == data.status) {
                    Public.tips.success("<?=__('删除成功！')?>");
                    location.reload();
                } else {
                    // showError(data.message);
                    Public.tips.error("<?=__('删除失败！')?>");
                }
            });
        });
    });
    
    function submitBtn() {
        $("#form").ajaxSubmit(function (message) {
            if (message.status == 200) {
                location.href = "index.php?ctl=Seller_Shop_Cat&met=cat";
            } else {
                Public.tips.error("<?=__('操作失败！')?>");
            }
        });
        return false;
    }
</script>
<?php
    include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

