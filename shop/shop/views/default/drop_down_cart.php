<div id="J_cart_pop" class="cart_pop">
    <?php if ($cart_count == 0) { ?>
        <div class="cart_empty"><i class="cart_empty_img"></i> 购物车中还没有商品，赶紧选购吧！</div>
    <?php } else { ?>
        <div class="cart_hd"><h4 class="cart_hd_title">最新加入的商品</h4></div>
        <div class="cart_bd J_cart_bd">
            <ul class="cart_singlelist"></ul>
            <ul class="cart_giftlist"></ul>
            <ul class="cart_suitlist"></ul>
            <ul class="cart_manjianlist"></ul>
            <ul class="cart_manzenglist">
                <li class="cart_item">
                    <ul class="cart_item_bd">
                        <?php $cart_price_num = 0; ?>
                        <?php foreach ($cart_goods_list as $goods_data) { ?>
                            <li class="cart_item">
                                <div class="cart_item_inner">
                                    <div class="cart_img">
                                        <a class="cart_img_lk" href="<?= Yf_Registry::get('url').'?ctl=Goods_Goods&met=goods&type=goods&gid='.$goods_data['goods_id'] ?>" target="_blank">
                                            <img src="<?= $goods_data['goods_image']; ?>" width="50" height="50" alt="<?= $goods_data['goods_name']; ?>">
                                        </a></div>
                                    <div class="cart_name">
                                        <a class="cart_name_lk" href="<?= Yf_Registry::get('url').'?ctl=Goods_Goods&met=goods&type=goods&gid='.$goods_data['goods_id'] ?>" title="<?= $goods_data['goods_name']; ?>" target="_blank"><?= $goods_data['goods_name']; ?></a>
                                    </div>
                                    <div class="cart_info">
                                        <?php $cart_price_num += $goods_data['now_price']*$goods_data['goods_num'] ?>
                                        <div class="cart_price">¥<?= "$goods_data[now_price]x$goods_data[goods_num]" ?></div>
                                        <a class="cart_delete J_delete" data-cart_id="<?= $goods_data['cart_id'] ?>" href="javascript:;">删除</a>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="cart_ft">
            <div class="cart_ft_info"> 共<span class="cart_num"><?php echo $cart_count;?></span>件商品　共计<span class="cart_num">¥ <?= number_format($cart_price_num, 2) ?></span>
            </div>
            <a class="cart_ft_lk" href="<?= Yf_Registry::get('url').'?ctl=Buyer_Cart&met=cart' ?>" title="去购物车">去购物车</a></div>
    <?php } ?>
</div>