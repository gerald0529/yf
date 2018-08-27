<script src="<?= $this->view->js_com ?>/plugins/jquery.imagezoom.small.js"></script>
<script>
    $(function(){
        $(".sjqzoom").simagezoom();
    });
</script>
<li style="display:block">
    <div class=" comment_1 clearfix">
        <div class="sppl">
            <div class="HPQ">
                <p class="font_1 bbc_color"><?=($good_pre)?><?=__('%')?></p>

                <p class="font_2"><?=__('好评价')?></p>
            </div>
            <div class="percent">
                <dl>
                    <dt><?=__('好评')?><span>(<?=($good_pre)?>%)</span>
                    </dt>
                    <dd>
                        <div>
                            <i style="width: <?=($good_pre)?>%" class="bbc_bg"></i>
                        </div>
                    </dd>
                </dl>
                <dl>
                    <dt><?=__('中评')?><span>(<?=($middle_pre)?><?=__('%')?>)</span>
                    </dt>
                    <dd class="d1">
                        <div>
                            <i style="width: <?=($middle_pre)?>%" class="bbc_bg"></i>
                        </div>
                    </dd>
                </dl>
                <dl>
                    <dt><?=__('差评')?><span>(<?=($bad_pre)?><?=__('%')?>)</span></dt>
                    <dd class="d1">
                        <div>
                            <i style="width: <?=($bad_pre)?>%" class="bbc_bg"></i>
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
        <ul class="goods_det_about clearfix evaluation">
            <li><a name="all" <?php if($type == 'all'): ?> class="checked" <?php endif;?>><?=__('全部评论')?>(<?=($all_count)?>)</a></li>
            <li><a name="image" <?php if($type == 'image'): ?>class="checked" <?php endif;?>> <?=__('晒图')?>(<?=($img_count)?>)</a></li>
            <li><a name="good" <?php if($type == 'good'): ?>class="checked" <?php endif;?>><?=__('好评')?>(<?=($good_count)?>)</a></li>
            <li><a name="middle" <?php if($type == 'middle'): ?>class="checked" <?php endif;?>> <?=__('中评')?>(<?=($middle_count)?>)</a></li>
            <li><a name="bad" <?php if($type == 'bad'): ?>class="checked" <?php endif;?>> <?=__('差评')?>(<?=($bad_count)?>)</a></li>
        </ul>
        <?php
        if(!empty($data['items'])):
            ?>
            <div class="explain">
                <?php foreach($data['items'] as $evkey=>$eval): ?>
                    <?php $evval = $eval[0]; ?>

                    <div class="comment clearfix">
                        <div class="detaildiv_1">
                            <p>
                                <?php for($i=1;$i<=$evval['scores'];$i++){?>
                                    <em class="em_1"></em>
                                <?php }?>

                            </p>
                            <p>
                                <?php if($evval['diff_time'] > 0){$diff_time = $evval['diff_time'];}else{$diff_time = "<?=__('当')?>";}?>
                                <?=__('收货'.$diff_time.'天后评论')?>
                            </p>
                            <time><?=($evval['create_time'])?></time>
                            <p>
                                <?php foreach($evval['goods_spec'] as $evskey => $evsval)
                                {
                                    echo $evsval." ";
                                }?>
                            </p>
                        </div>

                        <div class="detaildiv_2 ">
                            <?php if(isset($eval[1])): ?><?php endif; ?>
                            <?php Text_Filter::filterWords($evval['content']);?>
                            <p><?=($evval['content'])?>
                                <span>
                                    <?php foreach($evval['image_row'] as $imgkey => $imgval): ?>
                                        <a class="banimga">
                                            <?php if($imgval): ?>
                                                <img rel="<?=image_thumb($imgval,200,200)?>" src="<?=image_thumb($imgval,100,100)?>" class="sjqzoom">
                                            <?php endif;?>
                                        </a>
                                    <?php endforeach; ?>
                                </span>
                            </p>
                            
                            <?php Text_Filter::filterWords($evval['explain_content']);?>
                            <?php if($evval['explain_content']): ?><p><?=__('[解释]')?><?=($evval['explain_content'])?></p><?php endif;?>
                            <?php if(isset($eval[1])): ?><br/><p><?=__('[追加]')?><?php Text_Filter::filterWords($eval[1]['content']);?><?=($eval[1]['content'])?>
                                    <span><?php foreach($eval[1]['image_row'] as $img1key => $img1val): ?>
                                        <a class="banimga">
                                            <?php if($img1val): ?>
                                                <img rel="<?=image_thumb($img1val,200,200)?>" src="<?=image_thumb($img1val,100,100)?>" class="sjqzoom">
                                            <?php endif;?>
                                        </a>
                                    <?php endforeach; ?></span>
                                </p>
                                
                            <?php if($eval[1]['explain_content']): ?><p><?=__('[解释]')?><?php Text_Filter::filterWords($eval[1]['explain_content']);?><?=($eval[1]['explain_content'])?></p><?php endif;?>
                            <?php endif; ?>

                        </div>
                        <div class="detaildiv_3  ">
                            <p> <img src="<?=image_thumb($evval['user_grade_logo'],50,50)?>"> </p>
                            <p><?=($evval['user_name'])?></p>
                            <p><?=($evval['user_grade_name'])?></p>
                            <p></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
            </div>


            <div class="page page_front">
                <div>
                    <?=($page_nav)?>
                </div>
            </div>
            <?php
        else:
            ?>
                    <div class="no_account">
                        <img src="<?= $this->view->img ?>/ico_none.png"/>
                        <p><?= __('暂无符合条件的数据记录') ?></p>
                    </div>
            <?php
        endif;
        ?>
    </div>
</li>
<script>
    $(function(){
        $(".page").find("div a").click(function(){
            var url = $(this).attr('url');
            $("#goodseval").load(url, function(){
            });
        });
		
		$('.evaluation').find("li a").click(function(){
			$('.evaluation').find("li a").removeClass('checked');
			$(this).addClass('checked');
			load_goodseval($(this).attr('name'));
		});
		
		function load_goodseval(type) {
			var url =  SITE_URL  + '?ctl=Supplier_Goods&met=getGoodsEvaluationList&common_id=' + common_id + '&type=' + type ;
			$("#goodseval").load(url, function(){

			});
		}
    });
</script>