<li style="display:block" class="zl_1">
    <div class="sweet_warn clearfix">
        <p class="sweet_warn_img"></p>

        <div class="sweet_warn_text">
            <span><?=$head; ?></span>
            <a href="javascript:void(0);" id="add_consult" class="bbc_btns"><?=__('我要提问')?></a>
        </div>
    </div>

    <ul class="goods_det_about clearfix">
        <li><a><?=__('全部')?></a></li>
    </ul>
    <div class="consult_content clearfix ">
        <?php
        if (!empty($consult_base_data['items'])):
            foreach ($consult_base_data['items'] as $key_consult => $value_consult):
                ?>
                <div class="content clearfix">
                    <p>
                        <strong><?=__('提问用户：')?></strong>
                        <strong><?=$value_consult['user_name']?></strong>
                        <time style="text-align:right"><?= $value_consult['question_time'] ?></time>
                    </p>
                    <div class="clearfix">
                        <div class="arow_1 font_gray font_right">
                            <strong><?=__('咨询内容：')?></strong>
                        </div>
                        <div class="arow_2 font_gray wp85 tlf">
                            <span class="break"><?= $value_consult['consult_question'] ?></span>
                        </div>
                    </div>
                    <div class="font_3 clearfix">
                        <div class="arow_1 font_red font_right">
                            <strong><?=__('客服回复：')?></strong>
                        </div>
                        <div class="arow_2 font_red">
                            <?php if (!empty($value_consult['consult_answer']))
                            { ?>
                                <span><?= $value_consult['consult_answer'] ?></span>
                            <?php }
                            else
                            { ?>
                                <span><?=__('未答复')?></span>
                            <?php } ?>
                        </div>
                        <?php if($value_consult['consult_answer']): ?>
                            <div class="arow_3 font_red font_center">
                                <time><?= $value_consult['answer_time'] ?></time>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
            endforeach;
        ?>
            <div class="page page_front">
                <div colspan="5"><?=($page_nav)?></div>
            </div>
        <div class="bottom_1">
            <?=__('共有')?><?=$consult_base_data['records'] ?><?=__('个咨询')?> <a
                href="<?= Yf_Registry::get('url')?>?ctl=Buyer_Service_Consult&gid=<?=$goods_id; ?>"><?=__('查看全部咨询')?>
                <i class="iconfont icon-iconjiantouyou rel_top2"></i></a>
        </div>
        <?php else: ?>

                    <div class="no_account">
                        <img src="<?= $this->view->img ?>/ico_none.png"/>
                        <p><?= __('暂无符合条件的数据记录') ?></p>
                    </div>

        <?php endif;?>
    </div>
</li>
<script>
    $(function(){
        $(".page").find("div a").click(function(){
            var url = $(this).attr('url');
            $("#goodsadvisory").load(url, function(){
            });
        });
    });

    $("#add_consult").bind("click", function ()
    {
        if ($.cookie('key'))
        {
            if("<?=$goods_base['shop_id']?>" == "<?=Perm::$shopId?>")
            {
                Public.tips.warning('<?=__('不能咨询自己商店的商品！')?>');
            }
            else
            {
                $.dialog({
                    title: "<?=__('发起咨询')?>",
                    height: 400,
                    width: 700,
                    lock: true,
                    drag: false,
                    content: 'url: '+SITE_URL + '?ctl=Buyer_Service_Consult&met=add&typ=e&gid=<?=$goods_id ?>'
                });
            }

        }
        else
        {
            $("#login_content").show();
            load_goodseval(SITE_URL  + '?ctl=Index&met=fastLogin','login_content');
        }
    });
</script>