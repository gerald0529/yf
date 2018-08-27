<?php if (!defined('ROOT_PATH')) exit('No Permission'); ?>

<?php
    include $this->view->getTplPath() . '/' . 'seller_header.php';
    $tpl_flag = false;
?>
    </head>
    <body>
    <div class="freight">
        <div class="tabmenu">
            <ul>
                <li class="active bbc_seller_bg"><a><?= __('运费模板设置') ?></a></li>
            </ul>
            <a class="button bbc_seller_btns" href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Transport&met=transport&act=transport_default"><i class="iconfont icon-jia bbc_seller_btns"></i><?= __('添加运费模版') ?></a>
        </div>
        <form id="form" method="post">
            <table class="table-list-style" id="table_list" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="tl"><?= __('序号') ?></th>
                    <th class="tl"><?= __('模板名称') ?></th>
                    <th class="tl"><?= __('状态') ?></th>
                    <th><?= __('操作') ?></th>
                </tr>
                <?php if ($data) {
                    $num = 0;
                    foreach ($data as $key => $value) {
                        $num++;
                        ?>
                        <tr class="row_line">
                            <?php if (mb_strwidth($value['name'], 'utf8') > 50) {
                                $str = mb_strimwidth($value['name'], 0, 50, '...', 'utf8');
                            } else {
                                $str = $value['name'];
                            }
                            ?>
                            <td class="tl"><?= $num ?></td>
                            <td class="tl"><?= $str ?></td>
                            <td class="tl">
                                <?php
                                    if ($value['status'] == Transport_TemplateModel::TRANSPORT_TEMPLATE_OPEN) {
                                        echo __('启用');
                                        $tpl_flag = true;
                                    } else {
                                        echo __('暂停');
                                    }
                                ?>
                            </td>
                            <td>
                                <span class="edit">
                                    <a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Transport&met=transport&act=transport_default&id=<?= ($value['id']) ?>">
                                        <i class="iconfont icon-zhifutijiao"></i><?= __('编辑') ?>
                                    </a>
                                </span>
                                <span class="del">
                                    <a data-param="{'ctl':'Seller_Transport','met':'delTemplate','id':'<?= $value['id'] ?>'}" href="javascript:void(0)">
                                        <i class="iconfont icon-lajitong"></i>
                                        <?= __('删除') ?>
                                    </a>
                                </span>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="99">
                            <div class="no_account">
                                <img src="<?= $this->view->img ?>/ico_none.png" />
                                <p><?= __('暂无符合条件的数据记录') ?></p>
                                <p style="color:red;"><?= __('注：如果没有设置运费信息，将无法设置商品') ?></p>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </form>
    </div>
<?php
    include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>
<?php if (!$tpl_flag && SHOP_VERSION < '3.1.3') { ?>
    <script type="text/javascript">
        alert_box('请先添加一个运费模板，并开启');
    </script>
<?php } ?>