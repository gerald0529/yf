<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

    <div class="tabmenu">
        <ul class="tab pngFix">
            <li class="normal">
                <a href="index.php?ctl=Seller_Shop_Contract&met=index"><?=__('消费者保障服务')?></a>
            </li>
            <li class="active bbc_seller_bg">
                <a href=""><?=__('保障服务详情')?></a>
            </li>
        </ul>
    </div>

    <div class="ncsc-form-default ncsc-form-default-wid">
        <dl>
            <dt>
                <em class="pngFix"></em>
               <?=__('项目名称：')?> 
            </dt>
            <dd><?= $data['contract_type']['contract_type_name'] ?></dd>
        </dl>
        <dl>
            <dt>
                <em class="pngFix"></em>
                <?=__('所需保证金：')?>
            </dt>
            <dd><?= $data['contract_type']['contract_type_cash'] ?>&nbsp;元&nbsp;</dd>
        </dl>
        <dl>
            <dt>
                <em class="pngFix"></em>
                <?=__('状态：')?>
            </dt>
            <dd><?= $data['contract']['contract_state_text'] ?></dd>
        </dl>
        <h3><?=__('保障服务日志')?></h3>
        <table class="ncsc-default-table">
            <thead>
            <tr>
                <th class="w30"></th>
                <th class="w120 tl"><?=__('操作人')?></th>
                <th class="w200"><?=__('操作时间')?></th>
                <th class="tl"><?=__('操作描述')?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($data['log']['items'] as $key => $value)
            {
            ?>
            <tr class="bd-line">
                <td>&nbsp;</td>
                <td class="tl"><?= $value['contract_log_operator'] ?></td>
                <td><?= $value['contract_log_date'] ?></td>
                <td class="tl"><?= $value['contract_log_desc'] ?></td>
            </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="20">
                    <div class="flip clearfix">
                        <?=$data['page']?>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

