<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="tabmenu">
    <ul>
        <li class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('投诉管理')?></a></li>
    </ul>
</div>
    <div class="search fn-clear">
        <form id="search_form" method="get">
            <input type="hidden" name="ctl" value="Seller_Service_Complain"/>
            <input type="hidden" name="met" value="index"/>
            <a class="button refresh" href="index.php?ctl=Seller_Service_Complain&met=index&typ=e"><i class="iconfont icon-huanyipi"></i></a>
            <a class="button btn_search_goods"  href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
            <input type="text" name="keys" class="text w200" placeholder="<?=__('请输入投诉内容')?>" value="<?=$data['keys']?>"/>
            <select name="status" style="margin-right: 10px;">
                <option value=""><?=__('请选择状态')?></option>
                <option value="1" <?php if($data['state'] == 1){echo "selected='selected'";}?>><?=__('新投诉')?></option>
                <option value="2" <?php if($data['state'] == 2){echo "selected='selected'";}?>><?=__('待申诉')?></option>
                <option value="3" <?php if($data['state'] == 3){echo "selected='selected'";}?>><?=__('对话中')?></option>
                <option value="4" <?php if($data['state'] == 4){echo "selected='selected'";}?>><?=__('待仲裁')?></option>
                <option value="5" <?php if($data['state'] == 5){echo "selected='selected'";}?>><?=__('已关闭')?></option>
            </select>
            <input placeholder="开始时间" type="text" autocomplete="off" name="start_date" id="start_date" class="text w70" value="<?=$data['start_date']?>" style="float: none;margin: 0px;"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em>
            &nbsp;-&nbsp;
            <input placeholder="结束时间" type="text" autocomplete="off" name="end_date" id="end_date" class="text w70" value="<?=$data['end_date']?>" style="float: none;margin: 0px;"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em>
        </form>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#start_date').datetimepicker({
                controlType: 'select',
                timepicker:false,
                format:'Y-m-d'
            });

            $('#end_date').datetimepicker({
                controlType: 'select',
                timepicker:false,
                format:'Y-m-d'
            });
        });
        $(".search").on("click", "a.button", function ()
        {
            $("#search_form").submit();
        });
    </script>
</div>
<table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <th><?=__('投诉号')?></th>
        <th colspan="2"><?=__('投诉商品')?></th>
        <th><?=__('投诉主题')?></th>
        <th><?=__('投诉时间')?></th>
        <th><?=__('投诉状态')?></th>
        <th><?=__('操作')?></th>
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
            <td><?= $value['complain_id'] ?></td>
            <td width="65"><img width="60" src="<?= $value['good']['goods_image'] ?>"></td>
            <td width="450" style="text-align: left;"><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $value['good']['goods_id'] ?>"><?= $value['good']['goods_name'] ?></a></td>
            <td><?= $value['complain_subject_content'] ?></td>
            <td><?= $value['complain_datetime'] ?></td>
            <td><?= $value['complain_state_text'] ?></td>
            <td>
                <span><a href="./index.php?ctl=Seller_Service_Complain&met=index&act=detail&id=<?=$value['complain_id']?>"><i class="iconfont icon-chakan"></i><?=__('查看')?></a></span>
            </td>
        </tr>
    <?php } ?>
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
    <?php if($page_nav){ ?>
    <tr>
    <td class="toolBar" colspan="99">
        <div class="page">
            <?=$page_nav?>
        </div>
    </td>
    </tr>
    <?php }?>
    </tbody>
</table>
</div>
    <link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>