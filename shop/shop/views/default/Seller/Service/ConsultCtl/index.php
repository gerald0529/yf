<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<div class="tabmenu">
    <ul>
        <li <?php if (empty($data['state']))
        {
            echo 'class="active bbc_seller_bg"';
        } ?>><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Service_Consult&met=index"><?=__('全部咨询')?></a></li>
        <li <?php if ($data['state'] == 1)
        {
            echo 'class="active bbc_seller_bg"';
        } ?>><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Service_Consult&met=index&status=1"><?=__('未回复咨询')?></a></li>
        <li <?php if ($data['state'] == 2)
        {
            echo 'class="active bbc_seller_bg"';
        } ?>><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Service_Consult&met=index&status=2"><?=__('已回复咨询')?></a></li>
    </ul>
</div>
<div class="search fn-clear">
    <form id="search_form" method="get">
        <input type="hidden" name="ctl" value="Seller_Service_Consult"/>
        <input type="hidden" name="met" value="index"/>
        <a class="button refresh" href="index.php?ctl=Seller_Service_Consult&met=index&typ=e"><i class="iconfont icon-huanyipi"></i></a>
        <a class="button btn_search_goods" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
        <select name="type" style="margin-right: 10px;">
            <option value=""><?=__('请选择分类')?></option>
            <?php foreach($type as $v){ ?>
                <option value="<?=$v['consult_type_id']?>" <?php if($data['type']==$v['consult_type_id']){echo "selected='selected'";}?>><?=$v['consult_type_name']?></option>
            <?php } ?>
        </select>
    </form>
    <script type="text/javascript">
        $(".search").on("click", "a.button", function ()
        {
            $("#search_form").submit();
        });
    </script>
</div>
<form method="post" id="form" action="./index.php?ctl=Seller_Service_Consult&met=delAllConsult&typ=json">
    <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
            <th class="tl">
                <label class="checkbox"><input class="checkall" type="checkbox"></label><?=__('咨询/回复')?>
            </th>
            <th width="120"><?=__('操作')?></th>
        </tr>
        <?php
        if (!empty($data['items']))
        {
        ?>
        <?php
        foreach ($data['items'] as $key => $value)
        {
            ?>
            <tr>
                <td class="tl clearfix" colspan="2">
                    <label class="checkbox fl" style="margin-top: 3px;"><input class="checkitem" type="checkbox" name="chk[]" value="<?= $value['consult_id'] ?>"></label>
                   <div class="fl">
                   	 	<a style="float:left;margin-top:1px" target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?= $value['shop_id'] ?>"><i class="iconfont icon-icoshop"></i></a>
                    	<a  class="fl" target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $value['goods_id'] ?>"><?= $value['goods_name'] ?></a>
                		<p class="p_Font_1 fl" style="padding-left:3px"><a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?= $value['shop_id'] ?>">[<?= $value['shop_name'] ?>]</a></p>
                	
                   </div>
                </td>
            </tr>
            <tr>
                <td class="tl" style="padding-left: 38px;">
                    <p class="break replay-text">咨询内容：<?= $value['consult_question'] ?></p>
                    <?php if($value['consult_answer']){ ?><p class="break replay-text"><?=__('回复：')?><?= $value['consult_answer'] ?>(<?= $value['answer_time'] ?>)</p><?php }?>
                </td>
                <td>
                    <span class="edit"><a href="javascript:void(0)" data-id="<?= $value['consult_id'] ?>"><i
                                class="iconfont icon-jieshi"></i><?php if($value['consult_answer']){ echo __('修改');}?><?=__('回复')?></a></span>
                    <span class="del"><a
                            data-param="{'ctl':'Seller_Service_Consult','met':'delConsult','id':'<?= $value['consult_id'] ?>'}"
                            href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
                </td>
            </tr>
        <?php } ?>
        <td class="toolBar" colspan="99" <?php if(!$page_nav){echo "style='border-bottom:none;'";}?>>
            <input type="hidden" name="act" value="del">
            <label class="checkbox"><input class="checkall" type="checkbox"></label><?=__('全选')?>
            <span>|</span>
            <label class="del" data-param="{'ctl':'Seller_Service_Consult','met':'delAllConsult'}"><i class="iconfont icon-lajitong"></i><?=__('删除')?></label>
            <div class="page">
                <?=$page_nav?>
            </div>
        </td>
        </tr>
        <?php }else{ ?>
            <tr>
                <td colspan="99">
                    <div class="no_account">
                        <img src="<?= $this->view->img ?>/ico_none.png"/>
                        <p><?= __('暂无符合条件的数据记录') ?></p>
                    </div>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</form>

<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
<script>
    $(".edit").bind("click",function(){
        var id = $(this).children("a").attr("data-id");
        $.dialog({
            title:"<?=__('回复咨询')?>",
            height: 250,
            width: 550,
            lock: true,
            drag: false,
            content: 'url: '+SITE_URL + '?ctl=Seller_Service_Consult&met=reply&typ=e&consult_id=' + id
        });

    });
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>



