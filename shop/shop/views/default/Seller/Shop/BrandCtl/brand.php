<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
    include $this -> view -> getTplPath() . '/' . 'seller_header.php';
?>


<div class="tabmenu">
    <ul>
        <li class="active bbc_seller_bg"><a href="./index.php?ctl=Seller_Shop_Brand&met=brand&typ=e"><?= __('品牌申请') ?></a></li>
    
    </ul>
    <a id="add_brand" class="button add button_blue  bbc_seller_btns"><i class="iconfont  icon-jia"></i><?= __('添加品牌') ?></a>

</div>


<div class="search fn-clear">
    <div id="search_form">
        <form id="form" action="./index.php?ctl=Seller_Shop_Brand&met=brand" method="post">
            <a class="button refresh" href="<?= Yf_Registry ::get('url') ?>?ctl=Seller_Shop_Brand&met=brand&typ=e&"><i class="iconfont icon-huanyipi"></i></a> <a href="javascript:void(0);" class="button btn_search_goods"><i class="iconfont icon-btnsearch"></i><?= __('搜索') ?></a> <input type="text" value="" placeholder="<?= __('请输入品牌名称') ?>" class="text w200" name="brand_name">
        
        
        </form>
        <script type="text/javascript">
            $(".search").on("click", "a.button", function () {
                $("#form").submit();
            });
        </script>
    </div>
</div>
<table class="table-list-style" id="table_list" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <th width="100"><?= __('品牌图标') ?></th>
        <th><?= __('品牌名称') ?></th>
        <th width="200"><?= __('所属类别') ?></th>
        <th><?= __('审核状态') ?></th>
        <th width="120"><?= __('操作') ?></th>
    </tr>
    <?php if (!empty($data['items'])) {
        foreach ($data['items'] as $key => $value) { ?>
            <tr>
                
                
                <td><img src="<?= $value['brand_pic'] ?>!100X40.jpg"></td>
                <td><span class="number"><?= $value['brand_name'] ?></span></td>
                <td><span class="number"><?= $value['catname'] ?></span></td>
                <td>
                <span class="number">
                <?php if ($value['brand_enable'] == "0") { ?>
                    <?= __('未审核') ?>
                <?php } else { ?>
                    <?= __('已审核') ?>
                <?php } ?>
                    </span>
                </td>
                <td class="nscs-table-handle">
                    <?php if ($value['brand_enable'] == "0") { ?>
                        <span class="edit"><a class="edit_brand" data-brand="<?= $value['brand_id'] ?>"><i class="iconfont icon-zhifutijiao"></i><?= __('编辑') ?></a></span>
                        <span class="del"><a data-param="{'ctl':'Seller_Shop_Brand','met':'delBrand','id':'<?= $value['brand_id'] ?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?= __('删除') ?></a></span>
                    <?php } else { ?>
                        <span class="edit unclick"><a><i class="iconfont icon-zhifutijiao"></i><?= __('编辑') ?></a></span>
                        <span style="border-left: solid 1px #E6E6E6;" class="unclick"><a><i class="iconfont icon-lajitong"></i><?= __('删除') ?></a></span>
                    <?php } ?>
                </td>
            </tr>
        <?php }
    } else { ?>
        <tr class="row_line">
            <td colspan="99">
                <div class="no_account">
                    <img src="<?= $this -> view -> img ?>/ico_none.png">
                    <p><?= __('暂无符合条件的数据记录') ?></p>
                </div>
            </td>
        </tr>
    <?php } ?>
    <!--- 分页 -->
    <?php if (!empty($page_nav)) { ?>
        <tr>
            <td colspan="99">
                <div class="page">
                    <?= $page_nav ?>
                </div>
            </td>
        </tr>
    <?php } ?>
</table>


<link href="<?= $this -> view -> css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this -> view -> js_com ?>/plugins/jquery.dialog.js"></script>
<script>
    $("#add_brand").click(function () {
        $.dialog({
            title: "<?=__('添加品牌')?>",
            content: "url: " + SITE_URL + "?ctl=Seller_Shop_Brand&met=addBrandInfo&typ=e",
            width: 500,
            height: 410,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0,
            zIndex: 1
        });
        
    });
    
    $(".edit_brand").click(function () {
        var brand_id = $(this).attr("data-brand");
        $.dialog({
            title: "<?=__('编辑品牌')?>",
            content: "url: " + SITE_URL + "?ctl=Seller_Shop_Brand&met=editBrandInfo&typ=e&brand_id=" + brand_id,
            width: 500,
            height: 410,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0,
            zIndex: 1
        });
        
    });

</script>
<?php
    include $this -> view -> getTplPath() . '/' . 'seller_footer.php';
?>

