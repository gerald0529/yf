<script src="<?= $this->view->js_com ?>/plugins/jquery.imagezoom.small.js"></script>
<script>
    $(function(){
        $(".sjqzoom").simagezoom();
    });
</script>
<li style="display:block">
    <div class=" comment_1 clearfix">
        <div class="sppl clearfix pb20">
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
                    	<div class="detaildiv_3  ">
                            <p class="fl"> 
                            	<img src="<?=image_thumb($evval['user_grade_logo'],40,40)?>"> 
                            </p>
                            <ul class="fl">
                            	<li><?=($evval['user_name'])?></li>
                           		<li><?=($evval['user_grade_name'])?></li>
                            </ul>
                        </div>
                        <div class="detaildiv_1">
                            <p class="detaildiv_1_star">
                                <?php for($i=1;$i<=$evval['scores'];$i++){?>
                                    <em class="em_1"></em>
                                <?php }?>

                            </p>
                            
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
                            
                            <span class="c-999">
                                <?php foreach($evval['goods_spec'] as $evskey => $evsval)
                                {
                                    echo $evsval." ";
                                }?>
                            </span>
                            <time class='inline c-999'><?=($evval['create_time'])?></time>
                            
                            <?php Text_Filter::filterWords($evval['explain_content']);?>
                            <?php if($evval['explain_content']): ?><p class="bort1"><p class="c-999 pt10 pb5"><?=__('[解释]')?></p><p><?=($evval['explain_content'])?></p></p><?php endif;?>
                            <?php if(isset($eval[1])): ?><br/><p class="bort1"><p class="c-999 pt10 pb5"><?=__('[追加评价]')?>
                            	</p><p><?php Text_Filter::filterWords($eval[1]['content']);?><?=($eval[1]['content'])?></p>
                                    <span><?php foreach($eval[1]['image_row'] as $img1key => $img1val): ?>
                                        <a class="banimga">
                                            <?php if($img1val): ?>
                                                <img rel="<?=image_thumb($img1val,200,200)?>" src="<?=image_thumb($img1val,100,100)?>" class="sjqzoom">
                                            <?php endif;?>
                                        </a>
                                    <?php endforeach; ?></span>
                                </p>
                                
                            <?php if($eval[1]['explain_content']): ?><p class="bort1"><p class="c-999 pt10 pb5"><?=__('[解释]')?></p><p><?php Text_Filter::filterWords($eval[1]['explain_content']);?><?=($eval[1]['explain_content'])?></p></p><?php endif;?>
                            <?php endif; ?>

                        </div>
                            <!--<p>
                                <?php if($evval['diff_time'] > 0){$diff_time = $evval['diff_time'];}else{$diff_time = '当';}?>
                                <?=__('收货')?><?=($diff_time)?><?=__('天后评论')?>
                            </p>-->
                            
                            
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
			var url =  SITE_URL  + '?ctl=Goods_Goods&met=getGoodsEvaluationList&common_id=' + common_id + '&type=' + type ;
			$("#goodseval").load(url, function(){

			});
		}
    });
</script>