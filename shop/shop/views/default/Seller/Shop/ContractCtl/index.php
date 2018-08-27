<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
    <div class="tabmenu">
        <ul class="tab pngFix">
            <li class="active bbc_seller_bg"><a><?=__('消费者保障服务')?></a></li>
        </ul>
    </div>
    <div class="ncsc-form-default">
        <div style="width:100%;">

            <?php
            foreach ($data as $key => $value)
            {
                ?>
                <div style="float: left; width: 50%;">
                    <div
                        style="border: 1px solid #f5f5f5;background: none repeat scroll 0 0 #f5f5f5; margin: 20px 10px 0px 0px; height: 60px; padding: 30px;">
                        <div style="float: left; width: 60px; height: 60px;">
                            <img style="width: 60px;" src="<?= $value['contract_type_logo'] ?>">
                        </div>
                        <div style="float: left; margin-left: 15px; width:60%;">
                            <div style="height: 35px;">
                                <em style="float:left; margin-bottom: 12px;font-size: 16px;font-weight: 700;"><?= $value['contract_type_name'] ?></em>
                            </div>
                            <?php if (!empty($value['log_state']))
                            { ?>
                                <?php if (($value['state'] == 'inuse' && $value['log_state'] == "pass") || ($value['state'] == 'unuse' && $value['log_state'] == "unpass"))
                            { ?>
                                <div style="text-align: left;">
                                    <span><?=__('已加入')?></span>
                                    <a href="index.php?ctl=Seller_Shop_Contract&met=index&act=detail&id=<?= $value['contract_type_id'] ?>"
                                       style="margin-left: 30px;"><?=__('查看服务详情')?></a>
                                    |&nbsp;<a nc_type="quitbtn" href="javascript:void(0);"
                                              data-param="{'itemid':'<?= $value['contract_type_id'] ?>'}"><?=__('退出')?></a>
                                </div>
                            <?php }
                            elseif (($value['state'] == 'unuse' && $value['log_state'] == "pass") || ($value['state'] == 'inuse' && $value['log_state'] == "unpass"))
                            {
                                ?>

                                <div style="text-align: left;">
                                    <a title="<?=__('加入')?>" class="ncbtn ncbtn-mint" nc_type="applybtn"
                                       data-param="{'itemid':'<?= $value['contract_type_id'] ?>'}"><?=__('加入')?></a>
                                </div>
                            <?php }
                            else
                            { ?>
                                <div style="text-align: left;">
                                    <span><?=__('审核中')?></span>
                                    <a href="index.php?ctl=Seller_Shop_Contract&met=index&act=detail&id=<?= $value['contract_type_id'] ?>"
                                       style="margin-left: 30px;"><?=__('查看服务详情')?></a>
                                </div>
                            <?php } ?>
                            <?php }
                            else
                            {
                                ?>

                                <div style="text-align: left;">
                                    <a title="<?=__('加入')?>" class="ncbtn ncbtn-mint" nc_type="applybtn"
                                       data-param="{'itemid':'<?= $value['contract_type_id'] ?>'}"><?=__('加入')?></a>
                                </div>
                                <?php
                            } ?>
                        </div>
                    </div>
                    <div style="width: 100%;">
                        <div
                            style="min-height: 60px; margin-right: 10px; padding: 10px; color: #9c9c9c; border: 1px solid #f5f5f5;"><?= $value['contract_type_desc'] ?></div>

                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
    <script>
        $(document).ready(function ()
        {
            $("[nc_type='applybtn']").click(function ()
            {
                var data_str = $(this).attr('data-param');
                if (data_str)
                {
                    eval("data_str = " + data_str);
                    var itemid = parseInt(data_str.itemid);
                    $.post(SITE_URL + '?ctl=Seller_Shop_Contract&met=joinContract&typ=json', {contract_type_id: itemid}, function (data)
                    {
                        if (data && 200 == data.status)
                        {
                        
                             Public.tips.success("<?=__('加入成功，等待管理员审核！')?>");
                             location.reload();
                        }
                        else
                        {
                              Public.tips.error("<?=__('加入失败！')?>");
                        }
                    });
                }
            });
            $("[nc_type='quitbtn']").click(function ()
            {
                var data_str = $(this).attr('data-param');
                if (data_str)
                {
                    eval("data_str = " + data_str);
                    var itemid = parseInt(data_str.itemid);
                    $.post(SITE_URL + '?ctl=Seller_Shop_Contract&met=quitContract&typ=json', {contract_type_id: itemid}, function (data)
                    {
                        if (data && 200 == data.status)
                        {
                             Public.tips.success("<?=__('退出成功！')?>");
                             location.reload();
                        }
                        else
                        {
                               Public.tips.error("<?=__('退出失败！')?>");
                        }
                    });
                }
            });
        });
    </script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

