<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
       <div class="aright">
        <div class="buyer_center_list">
          <div class="my_orders">
            <p class="my_orders_tit clearfix"><span><?=__('我的订单')?></span><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical" target="_blank"><?=__('查看全部订单')?><i class ="iconfont icon-iconjiantouyou"></i></a></p>
            <!-- 有内容显示 -->
			<?php if(!empty($order['items'])){?>
            <table >
              <tbody>		 
			  <?php foreach($order['items'] as $key=>$val){?>
			  <?php if(!empty($val['goods_list'])){?>
                <tr>
                  <td class="my_orders_goods"><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$val['goods_list'][0]['goods_id']?>" target="_blank"><img src="<?php if($val['goods_list'][0]['goods_image']){?><?=image_thumb($val['goods_list'][0]['goods_image'],50,50)?><?php }else{?><?=image_thumb($this->web['goods_image'],50,50)?><?php }?>" height="50" width="50"/></a></td>
                  <td class="place_holder"><?=$val['goods_list'][0]['goods_name']?></td>
                  <td class="orders_goods_pri tc"><p><?=format_money($val['order_goods_amount'])?></p><p><?=$val['payment_name']?></p></td>
                  <td class="place_time tc"><p><?=$val['order_create_time']?></p></td>
                  <td class="order_pay_status z-hover tc"><p class="wait_pay bbc_color"><?=$val['order_state_con']?></p><p><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&act=details&order_id=<?=($val['order_id'])?>" class="pay_order_det" target="_blank"><?=__('订单详情')?></a></p></td>
                  <td class="w60 tc z-hover"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&act=details&order_id=<?=($val['order_id'])?>"><?=__('查看')?></a></td>
                </tr>
			  <?php } ?>
			  <?php } ?>
              </tbody>
            </table>
			 <?php }else{ ?>
            <!-- 没有内容显示 -->
            <div class="no_content_play vertical_top1" >
              <i class="iconfont icon-dingdan"></i><span><?=__('您买的东西太少了,这里都空空的,快去挑合适的商品吧！')?></span>
            </div>
			<?php } ?>
          </div>
          <div class="shop_cart_list">
            <p class="my_orders_tit clearfix"><span><?=__('购物车')?></span></span><a  href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Cart&met=cart" target="_blank"><?=__('查看所有商品')?><i class ="iconfont icon-iconjiantouyou"></i></a></p>
			<?php unset($cart['count']);?>
			<?php if(!empty($cart)){?>
             <!-- 有内容显示 -->
            <div class="shop_cart_list_div" >
              <ul class="shop_cart_list_ul clearfix">
				<?php foreach($cart as $key=>$val){?>
				<?php foreach($val['goods'] as $k=>$v){?>
                <li>
                  <div class="clearfix">
				  
                    <img src="<?php if(!empty($v['goods_base']['goods_image'])){?><?=image_thumb($v['goods_base']['goods_image'],50,50)?><?php }else{?><?= image_thumb($this->web['goods_image'],50,50)?><?php }?>" height="50" width="50"/>
                    <a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$v['goods_id']?>" target="_blank"><?=$v['goods_base']['goods_name']?></a>
                  </div>
                  <p class="clearfix"><a class ="bbc_btns" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Cart&met=confirm&product_id=<?=$v['cart_id'];?>"><?=__('去支付')?></a></p>
                </li>
                <?php } ?>
			  <?php } ?>
              </ul>
            </div>
			<?php }else{ ?>
            <!-- 没有内容显示 -->
             <div class="no_content_play disblock vertical_top2">
              <i class="iconfont icon-zaiqigoumai"></i><span><?=__('您的购物车还是空的哦！')?></span>
            </div>
			<?php } ?>
          </div>
         	<!--猜你喜欢-->
         	<!--<div class="buyer_goods_save">
            	<p class="my_orders_tit clearfix"><span>猜你喜欢</span></p>
            	<ul class="buyer_guess clearfix">
            		<li class="fl">
            			<div class="buyer_guess_img"></div>
            			<p class="buyer_guess_p1">￥199.00</p>
            			<p class="buyer_guess_p2 one-overflow">艾莱依长款加厚修修修修修修</p>
            		</li>
            	</ul>      		
         		
         	</div>-->
          	<!--猜你喜欢END-->
          <div class="buyer_goods_save">
            <p class="my_orders_tit clearfix"><span><?=__('商品收藏')?></span><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=favoritesGoods" target="_blank"><?=__('查看更多')?><i class ="iconfont icon-iconjiantouyou"></i></a></p>
             <!-- 有内容显示 -->
			<?php if(!empty($favoritesGoods['items'])){?>
            <div class="buyer_goods_savediv" >
			<a  class="btn_left btns iconfont icon-btnreturnarrow"data-num="0" data-numb="0"></a>
              <div class="goods_save_div">
                <ul class="goodsU buyer_goods_savelist clearfix">
				<?php foreach($favoritesGoods['items'] as $key=>$val){?>
				<?php if(!empty($val['detail'])){?>
                  <li>
                    <a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$val['detail']['goods_id']?>" target="_blank">
                      <img src="<?php if(!empty($val['detail']['goods_image'])){?><?=image_thumb($val['detail']['goods_image'],118,118)?><?php }else{?><?= image_thumb($this->web['goods_image'],118,118)?><?php }?>"/>
                      <p><?=format_money($val['detail']['goods_price'])?></p>
                    </a>
                  </li>
				  <?php }?>
				  <?php }?>
                </ul>
              </div>
			<a  class="btn_right btns iconfont icon-btnrightarrow"data-num="0" ></a>
            </div>
			 <?php }else{ ?>
            <!-- 没有内容显示 -->
             <div class="no_content_play vertical_top1">
              <i class="iconfont icon-shoucangshangping"></i><span><?=__('您还没有收藏任何商品,看到感兴趣的就果断收藏吧！')?></span>
            </div>
			<?php }?>
          </div>
          <div class="buyer_exc1">
            <div class="buyer_my_track">
              <p class="my_orders_tit clearfix"><span><?=__('我的足迹')?></span><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=footprint" target="_blank"><?=__('查看更多')?><i class ="iconfont icon-iconjiantouyou"></i></a></p>
			  <?php if(!empty($footprint['items'])){?>
               <!-- 有内容显示 -->
              <div class="buyer_my_track_div">
				<a  class="btn_left btns iconfont icon-btnreturnarrow"data-num="0" data-numb="0"></a>
                <div class="my_track_div">
                  <ul class="goodsU buyer_goods_savelist buyer_goods_savelistw clearfix">
					<?php foreach($footprint['items'] as $key=>$val){?>
					<?php if(!empty($val['detail'])){?>
                    <li>
                      <a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$val['detail']['goods_id']?>" target="_blank">
                        <img src="<?php if(!empty($val['detail']['common_image'])){?><?=image_thumb($val['detail']['common_image'],50,50)?><?php }else{?><?= image_thumb($this->web['goods_image'],50,50)?><?php }?>" height="50" width="50"/>
                        <h5><?=$val['detail']['common_name']?></h5>
                        <p><?=format_money($val['detail']['common_price'])?></p>
                      </a>
                    </li>
                   <?php }?>
				  <?php }?>
                  </ul>
                </div>
				<a  class="btn_right btns iconfont icon-btnrightarrow"data-num="0" ></a>
              </div>
			  <?php }else{ ?>
              <!-- 没有内容显示 -->
             <div class="no_content_play disblock">
              <i class="iconfont icon-zuji"></i><span><?=__('您的商品浏览记录为空')?></span>
            </div>
			<?php }?>
            </div>
          </div>
          <div class="buyer_exc2">
            <div class="buyer_store_collection">
              <p class="my_orders_tit clearfix"><span><?=__('店铺收藏')?></span><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=favoritesShop" target="_blank"><?=__('查看更多')?><i class ="iconfont icon-iconjiantouyou"></i></a></p>
			  <?php if(!empty($shop['items'])){?>
              <!-- 有内容显示 -->
              <div class="buyer_my_track_div">
               <a  class="btn_left btns iconfont icon-btnreturnarrow"data-num="0" data-numb="0"></a>
                <div class="store_collection_div">
                  <ul class="goodsU buyer_goods_savelist ulpd clearfix">
					<?php foreach($shop['items'] as $key=>$val){?>
					<?php if(!empty($val['detail'])){?>
                    <li>
                      <a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&typ=e&id=<?=$val['detail']['shop_id']?>" target="_blank">
                        <img src="<?php if(!empty($val['detail']['shop_logo'])){?><?=image_thumb($val['detail']['shop_logo'],120,40)?><?php }else{?><?= $this->web['shop_head_logo']?><?php }?>"  width="120"/>
                        <h5><?=$val['detail']['shop_name']?></h5>
                      </a>
                    </li>
					<?php }?>
					<?php }?>
                  </ul>
                </div>
				<a  class="btn_right btns iconfont icon-btnrightarrow" data-num="0" ></a>
              </div>
			  <?php }else{ ?>
               <!-- 没有内容显示 -->
              <div class="no_content_play">
                <i class="iconfont icon-shangjia"></i><span><?=__('您还没有收藏店铺哦！')?></span>
              </div>
			  <?php }?>
            </div>
          </div>
		  
        </div>
      </div>

    </div>
    
</div>
  </div>
 
</div>

</div>
  </div>
</div> 
<script>
 //商品滚动
    function doMove1(obj,attr,speed,target,callBack){
        if(obj.timer) return;
        var ww=obj.css(attr);
        var num = parseFloat(ww); 
        speed = num > target ? -Math.abs(speed) : Math.abs(speed);
        obj.timer = setInterval(function (){
            num += speed;
            if( speed > 0 && num >= target || speed < 0 && num <= target  ){
                num = target;
                clearInterval(obj.timer);
                obj.timer = null;
                var mm=num+"px";
                // obj.style[attr] = num + "px";
                obj.css(attr,mm);
                (typeof callBack === "function") && callBack();

            }else{
                var mm=num+"px";
                // obj.css(attr) = num + "px";
                 obj.css(attr,mm)
            }
        },30)   
    }
    var m=0;
    $(".btn_left").bind("click",function(){
        var W=$(this).parent().find("div").width();
        var goodsUl=$(this).parent().find(".goodsU");
        var ali=goodsUl.find("li");
        var rightA=$(this).parent().find(".btn_right");
        m=$(this).attr("data-numb");
        if(m<=0){
            m=0;
            return;
        }
        m--;
        $(this).attr("data-numb",m);
        rightA.attr("data-num",m);
        doMove1(goodsUl,"left",30, -m*W);

    })
    $(".btn_right").bind("click",function(){
        var W=$(this).parent().find("div").width();
        var goodsUl=$(this).parent().find(".goodsU");
        var ali=goodsUl.find("li");
        var n=goodsUl.find("li").width();
        var l=goodsUl.find("li").css("padding-left");
		l = l.replace("px","");

        goodsUl.css("width",(n+l*2)*ali.length);
        var ulW=goodsUl.width();
        var nums=Math.ceil(ulW/W);
        var leftA=$(this).parent().find(".btn_left");
        m=$(this).attr("data-num");
        if(m>=(nums-1)){
            return;
        }
        m++;
        $(this).attr("data-num",m);
        leftA.attr("data-numb",m);
        doMove1(goodsUl,"left",30,-m*W);
    })
</script>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>