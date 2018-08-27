<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
<style>
div.zoomMask{position:absolute;background:url("http://demo.lanrenzhijia.com/2015/jqzoom0225/images/mask.png") repeat scroll 0 0 transparent;cursor:move;z-index:1;}
</style>
<script src="<?= $this->view->js_com ?>/plugins/jquery.imagezoom.small.js"></script>
<script>
$(function(){
	$(".jqzoom").simagezoom();
});
</script>
</div> 
        <div class="order_content">
          <form id="goodsevalform" method="get" class="tc">
            <input name="act" value="member_evaluate" type="hidden">
            <input name="op" value="list" type="hidden">
            <input name="type" value="" type="hidden">
            <div class="evaluation-list">
              <?php if($data['items']):?>
                  <?php foreach($data['items'] as $key => $value):?>



            <div style="clear: both;"></div>
            <div class="evaluation-timeline clearfix">
                <!--S 商品信息 -->
                <div class="date">
                    <!-- 商品图片 -->
                    <div class="goods_image">
                        <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($value[0]['goods_id'])?>"><img src="<?=image_thumb($value[0]['goods_image'],86,86)?>"/></a>
                    </div>

                    <div class="order_goods">
                        <!-- 订单号 -->
                        <a target="_blank" ><?=($value[0]['order_id'])?></a>
                        <!-- 商品名称 -->
                        <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($value[0]['goods_id'])?>"><?=($value[0]['goods_name'])?></a>
                        <!-- 商品价格 -->
                        <p class="bbc_color price_pad"><?=format_money($value[0]['goods_price'])?></p>
                    </div>
                </div>
                <!--E 商品信息  -->


                <div style="clear: both;"></div>
                <div class="goods-thumb">
                    <!-- 用户头像 -->
                    <?php if(!empty($value[0]['user_logo']))
                                {
                                    $user_logo = $value[0]['user_logo'];
                                }else{
                                    $user_logo =$this->web['user_logo']; 
                                }
                    ?>
                    <img src="<?=image_thumb($user_logo,60,60)?>">
                    <!-- 用户名称 -->
                    <p><?=($value[0]['member_name'])?></p>
                </div>

                <!--S 评论内容  -->
                <dl class="detail detail_dls">
                  <dt class="clearfix">
                        <span><?=__('评论时间：')?><?=($value[0]['create_time'])?></span>
                        <span class="ml100">&nbsp;&nbsp;&nbsp;<?=__('商品评分：')?>
                            <em style="width: 100px;" title="<?=__('很满意')?>" class="raty" data-score="5">
                                <?php for($i=1;$i<=$value[0]['scores'];$i++):?>
                                <i class="iconfont icon-xingxing"></i>
                                <?php endfor; ?>
                                <input readonly value="<?=($value[0]['scores'])?>" name="score" type="hidden">
                            </em>
                        </span>
                  </dt>

                  <!-- 评价内容 -->
                  <?php Text_Filter::filterWords($value[0]['content']);?>
                  <dd class="c-333"><?=($value[0]['content'])?></dd>

                  <!-- 评价图片 -->
                  <div class="evaluate_img">
                      <?php if($value[0]['image_row']): foreach($value[0]['image_row'] as $img1key => $img1val ): ?>
                        <img src="<?=image_thumb($img1val,100,100)?>" class="jqzoom" rel="<?=image_thumb($img1val,200,200)?>">
                      <?php endforeach;endif;?>
                  </div>

                  <!--S 解释内容  -->
                <?php if($value[0]['explain_content']): ?>
                    <div style="clear: both;"></div>
                    <dl class="detail_dls detail_explain_color">
                        <?php Text_Filter::filterWords($value[0]['explain_content']);?>
                        <dd><strong class="c-333"><?=__('[商家解释]')?></strong><?=($value[0]['explain_content'])?></dd>
                          <dd>
                             <span  class="c-999"><?=($value[0]['update_time'])?></span>
                        </dd>
                    </dl>
                <?php endif; ?>
                <!--E 解释内容  -->
                <?php if(!isset($value[1])):?>
                  <span>
                    <a class="btnredpj bbc_seller_btns" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation&act=again&oge_id=<?=($value[0]['evaluation_goods_id'])?>"  id="explain"><i class=" bbc_seller_btns iconfont icon-shangjiaruzhushenqing icon_size16"></i><?=__('追加评价')?></a>
                  </span>
                <?php endif;?>

                </dl>
                <!--E 评论内容  -->


                <!--S 追加评价  -->
                <?php if(isset($value[1])):?>
                    <div style="clear: both;"></div>
                    <div class="goods-thumb"></div>
                    <!--S 追加评论评论内容  -->
                    <dl class="detail detail_dls">
                      <dt class="clearfix">
                            <span><?=__('追加时间：')?><?=($value[1]['create_time'])?></span>
                      </dt>

                      <!-- 追加评价内容 -->
                      <?php Text_Filter::filterWords($value[1]['content']);?>
                      <dd><?=($value[1]['content'])?></dd>

                      <!-- 追加评价图片 -->
                      <div class="evaluate_img">
                        <?php if($value[1]['image_row']): foreach($value[1]['image_row'] as $img2key => $img2val ): ?>
                            <img src="<?=image_thumb($img2val,100,100)?>" class="jqzoom" rel="<?=image_thumb($img2val,200,200)?>">
                        <?php endforeach;endif;?>
                      </div>

                        <!--S 追加评论解释内容  -->
                        <?php if($value[1]['explain_content']): ?>
                            <div style="clear: both;"></div>
                            <dl class="detail_dls detail_explain_color">
                                <?php Text_Filter::filterWords($value[1]['explain_content']);?>
                                <dd><strong class="c-333"><?=__('[商家解释]')?></strong><?=($value[1]['explain_content'])?></dd>
                          <dd>
                             <span  class="c-999"><?=($value[1]['update_time'])?></span>
                        </dd>
                            </dl>
                        <?php endif; ?>
                        <!--E 追加评论解释内容  -->
                    </dl>
                    <!--E 追加评论内容  -->
                <?php endif;?>
                <!--E 追加评价  -->

              </div>




                  <?php endforeach;?>
              <?php endif;?>
            </div>
            <div class="flip page clearfix">
            <?=$page_nav?>
              <!--<p><a href="#" class="page_first">首页</a><a href="#" class="page_prev">上一页</a><a href="#" class="numla cred">1</a><a href="#" class="page_next">下一页</a><a href="#" class="page_last">末页</a></p>-->
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 尾部 -->

 <?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>