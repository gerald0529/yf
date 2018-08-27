<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
<table class="table-list-style table-promotion-list" id="table_list" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <th class="tl"><?=__('活动名称')?></th>
        <th width="250"><?=__('开始时间-结束时间')?></th>
        <th width="300"><?=__('活动内容')?></th>
        <th width="110"><?=__('状态')?></th>
    </tr>
    <style>

    </style>
    <?php
        if(@$data){
    ?>
        <tr class="line_row">
            <td class="tl"><?=@$data['mansong_name']?></td>
            <td>
                <p></p><?=@$data['mansong_start_time']?></p>
                <p><?=__('至')?></p>
                <p><?=@$data['mansong_end_time']?></p>
            </td>
            <td>
                <ul class="ncsc-mansong-rule-list">
                    <?php
                    foreach(@$data['rule'] as $key=>$value)
                    {
                    ?>
                    <li>
                        <?=__('单笔订单满')?><strong><?=@format_money($value['rule_price'])?></strong>，<?=__('立减现金')?><strong><?=@format_money($value['rule_discount'])?></strong>
                        <?php
                            if($value['goods_image'])
                            {
                        ?>
                                <span>，<?=__('送礼品')?></span>
                                <a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$value['goods_id']?>" title="<?=$value['goods_name']?>" target="_blank" class="goods-thumb">
                                    <img src="<?=image_thumb($value['goods_image'],32,32)?>">
                                </a>
                        <?php
                            }
                        ?>
                    </li>
                    <?php  } ?>
                </ul>
            </td>
            <td><?=@$data['mansong_state_label']?></td>
        </tr>
    <?php
    }
    ?>
</table>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>
