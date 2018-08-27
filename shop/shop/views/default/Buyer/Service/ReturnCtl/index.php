<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/headfoot.css">
<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/iconfont/iconfont.css">
<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/index.css">
<div class="aright">
    <div class="member_infor_content">
        <div class="order_content">
            <div class="div_head  tabmenu clearfix">
                <ul class="tab pngFix clearfix">
                    <li <?php if (empty($data['state']) || $data['state'] == 1)
                    {
                        echo 'class="active"';
                    } ?>>
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&state=1"><?= __('退款申请') ?></a>
                    </li>
                    <li <?php if ($data['state'] == 2)
                    {
                        echo 'class="active"';
                    } ?>>
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&state=2"><?= __('退货申请') ?></a>
                    </li>
                    <li <?php if ($data['state'] == 3)
                    {
                        echo 'class="active"';
                    } ?>>
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&state=3"><?= __('虚拟兑码退款') ?></a>
                    </li>
                </ul>
            </div>

            <div class="order_content_title clearfix">
                <div style="margin-top: 10px;" class="clearfix">
                    <form id="search_form" method="get">
                        <input type="hidden" name="ctl" value="Buyer_Service_Return"/>
                        <input type="hidden" name="met" value="index"/>
                        <input type="hidden" name="state" value="<?php echo $state;?>"/>
                        <p class="pright">
                            <span style="line-height: 25px;"><?= __('申请时间') ?></span> <input type="text" name="start_time" id="start_time" class="A" value="<?= $start_time ?>" placeholder="<?=__('开始时间')?>"><em style="line-height: 25px;">&nbsp;–&nbsp;</em><input type="text" name="end_time" id="end_time" class="A" value="<?= $end_time ?>" placeholder="<?=__('结束时间')?>"><span style="line-height: 25px;margin-left: 8px;"><?= __('退款编号') ?></span><input type="text" name="return_code" class="A" style=" margin-left: 2px;width: 150px;" value="<?= $order_id ?>" placeholder="<?=__('退款编号')?>"> <a href="javascript:void(0);" class="sous" style="margin-right: 0;"><i class="iconfont icon-btnsearch"></i><?= __('搜索') ?></a><div style="clear:both;"></div></p>
                    </form>
                </div>
            </div>
            <link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css?ver=<?= VER ?>"
                  rel="stylesheet" type="text/css">
            <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.datetimepicker.js"
                    charset="utf-8"></script>
            <script type="text/javascript">
                $(".sous").on("click", function ()
                {
                    $("#search_form").submit();
                });
                $('#start_time').datetimepicker({
                    controlType: 'select',
                    format: "Y-m-d",
                    timepicker: false
                });

                $('#end_time').datetimepicker({
                    controlType: 'select',
                    format: "Y-m-d",
                    timepicker: false
                });
            </script>

            <table style="width: 100%;" class="icos">
                <tbody class="tbpad">
                <tr class="order_tit">
                    <th colspan="2" width="250" class="tlf pl-10"><?= __('退款商品') ?></th>
                    <th width="100"><?= __('退款数量') ?></th>
                    <th width="100"><?= __('退款金额') ?></th>
                    <th><?= __('退款编号') ?></th>
                    <th width="200"><?= __('审核状态') ?></th>
                    <th width="<?php if ($data['state'] == 2){echo 89;}else{echo 146;} ?>"><?= __('操作') ?></th>
                </tr>
                </tbody>
                <tbody>
                <tr>
                    <th class="tr_margin" style="height:16px;background:#fff;" colspan="8"></th>
                </tr>
                </tbody>
                <?php
                if (!empty($data['items']))
                {
                    ?>
                    <?php
                    foreach ($data['items'] as $key => $value)
                    {
                        ?>
                        <tbody class="tboy">
                        <tr class="tr_title">
                            <th colspan="7" class="order_mess clearfix">
                                <p class="order_mess_one">
                                    <time><?= $value['return_add_time'] ?></time>
                                </p>
                            </th>
                        </tr>
                        <tr class="tr_con">
                            <td class="pl-10 tlf pr-10"><img width="60" src="<?=$value['order_goods_pic'] ?>"></td>
                            <td width="200" class="tlf"><!-- <a class="return-goods-href" href="./index.php?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $value['order_goods_id'] ?>"> --><?=$value['order_goods_name']?><!-- </a> --></td>
                            <td>x<?=$value['order_goods_num']?></td>
                             <td class="td_color"><?= format_money($value['return_cash']) ?></td>
                            <td><?= $value['return_code'] ?></td>
                            <td class="td_color"><?= $value['return_state_text'] ?></td>
                            <td><span><a
                                        href="./index.php?ctl=Buyer_Service_Return&met=index&act=detail&id=<?= $value['order_return_id'] ?>"><i
                                            class="iconfont icon-chakan"></i><?=__('查看')?></a></span></td>
                        </tr>
                        </tbody>
                    <?php } ?>

                <?php }
                else
                {
                    ?>
                    <tbody>
                    <tr>
                        <td colspan="99">
                            <div class="no_account">
                                <img src="<?= $this->view->img ?>/ico_none.png"/>
                                <p><?= __('暂无符合条件的数据记录') ?></p>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                <?php } ?>
            </table>

            <?php if ($page_nav)
            { ?>
                <div class="page"><?= $page_nav ?></div>
            <?php } ?>
        </div>
    </div>
</div>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>



