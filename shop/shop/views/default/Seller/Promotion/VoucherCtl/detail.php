<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="content">
    <div class="form-style">
        <dl>
            <dt><?=__('代金券名称')?>：</dt>
            <dd><?=$data['voucher_t_title']?></dd>
        </dl>
        <dl>
            <dt><?=__('店铺分类')?>：</dt>
            <dd><?=$data['shop_class_name']?></dd>
        </dl>

        <dl>
            <dt><?=__('领取方式')?>：</dt>
            <dd><?=$data['voucher_t_access_method_label']?></dd>
        </dl>

        <dl>
            <dt><?=__('有效期')?>：</dt>
            <dd><?=$data['voucher_t_end_date']?></dd>
        </dl>
        <dl>
            <dt><?=__('面额')?>：</dt>
            <dd><?=format_money($data['voucher_t_price'])?></dd>
        </dl>
        <dl>
            <dt><?=__('兑换所需积分')?>：</dt>
            <dd><?=$data['voucher_t_points']?> <?=__('分')?></dd>
        </dl>
        <dl>
            <dt><?=__('可发放总数')?>：</dt>
            <dd><?=$data['voucher_t_total']?> <?=__('张')?></dd>
        </dl>
        <dl>
            <dt><?=__('每人限领')?>：</dt>
            <?php if($data['voucher_t_eachlimit'] == 0){  ?>
            <dd><?=__('不限')?></dd>
            <?php }else{ ?>
            <dd><?=$data['voucher_t_eachlimit']?> <?=__('张')?></dd>
            <?php } ?>
        </dl>
        <dl>
            <dt><?=__('消费金额')?>：</dt>
            <dd><?=format_money($data['voucher_t_limit'])?></dd>
        </dl>

        <dl>
            <dt><?=__('会员级别')?>：</dt>
            <dd><?=$data['voucher_t_user_grade_limit_label']?></dd>
        </dl>
        <dl>
            <dt><?=__('代金券描述')?>：</dt>
            <dd>
                <textarea name="voucher_t_desc" readonly class="text textarea w450"><?=$data['voucher_t_desc']?></textarea>
            </dd>
        </dl>
        <dl>
            <dt><?=__('代金券图片')?>：</dt>
            <dd>
               <img id="image_review" src="<?=image_thumb($data['voucher_t_customimg'],200,200)?>" height="200" width="200" />
            </dd>
        </dl>
        <dl>
            <dt><?=__('最后修改时间')?>：</dt>
            <dd><?=$data['voucher_t_update_date']?></dd>
        </dl>
        <dl>
            <dt><?=__('状态')?>：</dt>
            <dd><?=$data['voucher_t_state_label']?></dd>
        </dl>
        <dl>
            <dt><?=__('已领取')?>：</dt>
            <dd><?=$data['voucher_t_giveout']?> <?=__('张')?></dd>
        </dl>
        <dl>
            <dt><?=__('已使用')?>：</dt>
            <dd><?=$data['voucher_t_used']?> <?=__('张')?></dd>
        </dl>
    </div>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

