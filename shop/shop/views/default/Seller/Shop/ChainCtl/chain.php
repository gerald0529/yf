<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<div class="tabmenu">
    <ul>
        <li class="active bbc_seller_bg"><a href="./index.php?ctl=Seller_Shop_Chain&amp;met=chain&amp;typ=e"><?=__('门店列表')?></a></li>

    </ul>
    <a class="button add button_blue bbc_seller_btns" href="./index.php?ctl=Seller_Shop_Chain&amp;met=chain&amp;act=add&amp;typ=e"><i class="iconfont icon-jia"></i><?=__('添加门店')?></a>

</div>
<form id="form" action="./index.php?ctl=Seller_Shop_Chain&met=delAllChain&typ=json" method="post" onsubmit="return submitBtn();">
<table class="table-list-style">
    <thead>
    <tr>
        <th class="tl" width=200;><label class="checkbox"><input class="checkall" type="checkbox"></label><?=__('门店名称')?></th>
        <th width=200;><?=__('所在地区')?></th>
        <th class="tc"><?=__('门店地址')?></th>
        <th width=150;><?=__('操作')?></th>
    </tr>
    </thead>
    <tbody>
    <?php if(!empty($data['items'])){
        //echo '<pre>';print_r($data);exit;
        foreach($data['items'] as $key => $val){?>
    <tr class="bd-line">
        <td class="tl"><label class="checkbox"><input type="checkbox" class="checkitem" name="chk[]" value="<?=$val['chain_id']?>"></label><?=$val['chain_name']?></td>
        <td><?=$val['chain_province']?>&nbsp;<?=$val['chain_city']?>&nbsp;<?=$val['chain_county']?></td>
        <td><?=$val['chain_address']?></td>
        <td class="nscs-table-handle">
            <span><a href="./index.php?ctl=Seller_Shop_Chain&amp;met=chain&amp;act=edit&amp;typ=e&amp;chain_id=<?=$val['chain_id']?>" class="btn-bluejeans"><i class="iconfont icon-zhifutijiao"></i><p><?=__('编辑')?></p></a></span>
            <span class="delparent del" ><a data-param="{'ctl':'Seller_Shop_Chain','met':'delChain','id':'<?=$val['chain_id']?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><p><?=__('删除')?></p></a></span>
        </td>
    </tr>
<?php } ?>
    </tbody
    <tfoot>
    <tr>
        <td class="toolBar" colspan="99">
            <input type="hidden" name="op" value="del" />
            <label class="checkbox"><input class="checkall" type="checkbox"></label><?=__('全选')?>            <span>|</span>

            <label class="del"><a data-param="{'ctl':'Seller_Shop_Chain','met':'delAllChain'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></label>

        </td>
    </tr>
    <?php if(!empty($page_nav)){?>
        <tr>
            <td colspan="99">
                <div class="page">
                    <?=$page_nav?>
         `       </div>
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
    </tfoot>
</table>
</form>

    <script>
        //============删除操作========
        $('span.delparent a').click(function() {
                var e = $(this);
                var data_str = e.attr('data-param');
                eval("data_str = " + data_str);
                if (confirm('<?=__('您确定要删除吗？')?>')) {
                    $.post(SITE_URL + '?ctl=' + data_str.ctl + '&met=' + data_str.met + '&typ=json', {chain_id: data_str.id}, function (data) {
                            //alert(JSON.stringify(data));
                            if (data && 200 == data.status) {
                                location.href="index.php?ctl=Seller_Shop_Chain&met=chain";
                                //Public.tips.success('删除成功!');
                            }
                            else {
                                // showError(data.message);
                                Public.tips.error("<?=__('操作失败！')?>");
                            }
                        }
                    );
                }
            }
        )


        function submitBtn()
        {
            $("#form").ajaxSubmit(function(message){
                if(message.status == 200)
                {
                    location.href="index.php?ctl=Seller_Shop_Cat&met=cat";
                }
                else
                {
                    Public.tips.error("<?=__('操作失败！')?>");
                }
            });
            return false;
        }
    </script>
<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

