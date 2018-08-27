<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="tabmenu">
    <ul>
        <li class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('退款管理')?></a></li>
    </ul>
</div>
<div class="search fn-clear">
    <form id="search_form" method="get">
        <input type="hidden" name="ctl" value="Seller_Service_Return"/>
        <input type="hidden" name="met" value="orderReturn"/>
        <a class="button refresh" href="index.php?ctl=Seller_Service_Return&met=orderReturn&typ=e"><i class="iconfont icon-huanyipi"></i></a>
        <a class="button btn_search_goods" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>
        <input type="text" name="keys" class="text w200" placeholder="<?=__('请输入商品名称')?>" value="<?= $data['keys'] ?>"/>
        <select name="status" style="margin-right: 10px;">
            <option value=""><?=__('请选择状态')?></option>
            <option value="1" <?php if ($data['state'] == 1)
            {
                echo "selected='selected'";
            } ?>><?=__('新退款')?>
            </option>
            <option value="2" <?php if ($data['state'] == 2)
            {
                echo "selected='selected'";
            } ?>><?=__('卖家通过')?>
            </option>
            <option value="4" <?php if ($data['state'] == 4)
            {
                echo "selected='selected'";
            } ?>><?=__('等待平台确认')?>
            </option>
            <option value="5" <?php if ($data['state'] == 5)
            {
                echo "selected='selected'";
            } ?>><?=__('平台确认')?>
            </option>
            <option value="3" <?php if ($data['state'] == 3)
            {
                echo "selected='selected'";
            } ?>><?=__('卖家不通过')?>
            </option>
        </select>
        <input type="text" autocomplete="off" placeholder="开始时间" name="start_date" id="start_date" class="text w70" value="<?=$data['start_date']?>" style="float: none;margin: 0px;"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em>
        &nbsp;-&nbsp;
        <input type="text" autocomplete="off" placeholder="结束时间" name="end_date" id="end_date" class="text w70" value="<?=$data['end_date']?>" style="float: none;margin: 0px;"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em>
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
        <td><?=__('商品/订单号/退款号')?></td>
        <td><?=__('退款金额')?></td>
        <td><?=__('买家会员名')?></td>
        <td><?=__('申请时间')?></td>
        <td><?=__('处理状态')?></td>
        <td><?=__('操作')?></td>
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
            <td>
                <dl class="fn-clear">
                    <dd class="alr">
                        <a style="float: left; padding: 5px;" class="aimg"><img width="60" height="60" src="<?= $value['order_goods_pic'] ?>"></a>
                        <div style="float:left;">
                            <h3><a style="text-overflow: ellipsis;width:350px;height: 20px;overflow: hidden;line-height: 26px;white-space: nowrap;" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $value['good']['goods_id'] ?>" target="_blank" title="<?= $value['order_goods_name'] ?>"><?= $value['order_goods_name'] ?></a></h3>
                            <p style="padding-top: 3px;"><?=__('订单编号:')?><?= $value['order_number'] ?></p>
                            <p style="padding-top: 3px;" class="getit"><?=__('退款编号:')?><?= $value['return_code'] ?></p>
                        </div>
                    </dd>
                </dl>
            </td>
            <td>￥<?= $value['return_cash'] ?></td>
            <td><?= $value['buyer_user_account'] ?></td>
            <td><?= $value['return_add_time'] ?></td>
            <td><?= $value['return_state_text'] ?></td>
            <td>
                <?php if(isset($dist_common_ids) && in_array($value['common_id'],$dist_common_ids)){?>
            		<span></span>
            	<?php }else{?>
                    <span><a
                            href="./index.php?ctl=Seller_Service_Return&met=orderReturn&act=detail&id=<?= $value['order_return_id'] ?>"><i class="iconfont icon-chakan"></i><?=__('查看')?></a></span>
                <?php }?>
            </td>
        </tr>
    <?php } ?>
    <?php if($data['page']){ ?>
    <tr>
    <td class="toolBar" colspan="99">
        <div class="page">
            <?=$data['page']?>
        </div>
    </td>
    </tr>
    <?php }?>
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
</div>
    <link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>