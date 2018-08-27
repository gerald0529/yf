<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
    <script type='text/jade' id='thrid_opt'>
				<a class="bbc_seller_btns button add button_blue" href="<?= Yf_Registry::get('index_page') ?>?ctl=Seller_Goods&met=format&typ=e&act=add"><i class="iconfont icon-jia"></i><?=__('添加新版式')?></a>



    </script>
    <script type="text/javascript">
        $(function ()
        {
            $('.tabmenu').append($('#thrid_opt').html());
        });
    </script>
    <div class="search fn-clear">
        <div class="alert mt15 mb5"><strong><?=__('操作提示')?>：</strong>
            <ul>
                <li>1、<?=__('关联版式可以把预设内容插入到商品描述的顶部或者底部，方便商家对商品描述批量添加或修改。')?></li>
            </ul>
        </div>
        <form id="search_form" class="search_form_reset" method="get" action="<?= Yf_Registry::get('url') ?>">
            <input class="text w150" type="text" name="search" value="" placeholder="<?=__('请输入版式名称')?>"/>
            <input type="hidden" name="ctl" value="Seller_Goods">
            <input type="hidden" name="met" value="format">
            <a class="button refresh" href="index.php?ctl=Seller_Goods&met=format&typ=e"><i
                    class="iconfont icon-huanyipi"></i></a>
            <a class="button btn_search_goods" href="javascript:void(0);"><i
                    class="iconfont icon-btnsearch"></i><?= __('搜索') ?></a>
        </form>
    </div>
    <script type="text/javascript">
        $(".search").on("click", "a.button", function ()
        {
            $("#search_form").submit();
        });
    </script>

<?php
if (!empty($data))
{
    ?>
    <form id="form" method="post" action="index.php?ctl=Seller_Goods&met=editGoodsCommon">
        <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <th class="tl">
                    <label class="checkbox"><input class="checkall" type="checkbox"/></label><?=__('版式名称')?>
                </th>
                <th width="80"><?=__('位置')?></th>
                <th width="120"><?=__('操作')?></th>
            </tr>
            <?php
            foreach ($data as $key => $value)
            {

                ?>
                <tr>

                    <td class="tl">
                        <label class="checkbox"><input class="checkitem" type="checkbox" name="chk[]"
                                                       value="<?= $value['id'] ?>"/></label>
                        <?= $value['name'] ?>
                    </td>
                    <td><?php print($value['position_name']); ?></td>
                    <td>

                        <span class="edit"><a
                                href="<?php echo Yf_Registry::get('url'); ?>?ctl=Seller_Goods&met=format&act=edit&id=<?= $value['id']; ?>"><i
                                    class="iconfont icon-zhifutijiao"></i><?=__('编辑')?></a></span>
                        <span class="del"><a
                                data-param="{'id':'<?= $value['id']; ?>','ctl':'Seller_Goods','met':'deleteGoodsFormat'}"
                                href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>

                    </td>
                </tr>
            <?php
            }?>
            <tr>
                <td class="toolBar" colspan="1">
                    <input type="hidden" name="act" value="del"/>
                    <label class="checkbox"><input class="checkall" type="checkbox"/></label><?=__('全选')?>
                    <span>|</span>
                    <label class="del" data-param="{'ctl':'Seller_Goods','met':'deleteGoodsFormatRows'}"><i
                            class="iconfont icon-lajitong"></i><?=__('删除')?></label>
                </td>
                <td colspan="99">
                    <div class="page">
                        <?= $page_nav ?>
                    </div>
                </td>
            </tr>
        </table>
    </form>
<?php
}
else
{
    ?>
    <form id="form" method="post" action="index.php?ctl=Seller_Goods&met=editGoodsCommon">
        <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <th class="tl">
                    <label class="checkbox"><input class="checkall" type="checkbox"/></label><?=__('版式名称')?>
                </th>
                <th width="80"><?=__('位置')?></th>
                <th width="120"><?=__('操作')?></th>
            </tr>
        </table>
    </form>
    <div class="no_account">
        <img src="<?=$this->view->img?>/ico_none.png">
        <p><?= __('暂无符合条件的数据记录'); ?></p>
    </div>
<?php } ?>

    <link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
    <script type="text/javascript">
        $('.dropdown').hover(function ()
        {
            $(this).addClass("hover");
        }, function ()
        {
            $(this).removeClass("hover");
        });
    </script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>